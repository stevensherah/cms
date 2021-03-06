<?php

namespace Photon\PhotonCms\Core\Controllers;

use Carbon\Carbon;
use Config;
use DB;
use Schema;
use Illuminate\Support\Facades\Artisan;
use Photon\Http\Controllers\Controller;

// Dependency injection
use Photon\PhotonCms\Core\Response\ResponseRepository;
use Photon\PhotonCms\Core\Exceptions\PhotonException;
use Photon\PhotonCms\Core\Entities\Seed\SeedTemplate;
use Photon\PhotonCms\Core\Entities\Seed\SeedRepository;
use Photon\PhotonCms\Core\Entities\Seed\SeedGateway;
use Photon\PhotonCms\Core\Entities\Module\ModuleRepository;
use Photon\PhotonCms\Core\Entities\Model\ModelRepository;
use Photon\PhotonCms\Core\Entities\Model\Contracts\ModelGatewayInterface;
use Photon\PhotonCms\Core\Entities\Model\ModelCompiler;
use Photon\PhotonCms\Core\Entities\ModelRelation\ModelRelationFactory;
use Photon\PhotonCms\Core\Entities\Module\Contracts\ModuleGatewayInterface;
use Illuminate\Filesystem\Filesystem;
use Photon\PhotonCms\Core\Entities\Migration\Contracts\MigrationGatewayInterface;
use Photon\PhotonCms\Core\Entities\DynamicModuleMigration\DynamicModuleMigrationRepository;
use Photon\PhotonCms\Core\Entities\DynamicModule\DynamicModuleLibrary;
use Photon\PhotonCms\Core\Entities\DynamicModule\DynamicModuleRepository;
use Photon\PhotonCms\Core\Entities\DynamicModule\DynamicModuleGateway;
use Photon\PhotonCms\Core\Entities\Migration\MigrationCompiler;
use Photon\PhotonCms\Core\Helpers\DatabaseHelper;
use Photon\PhotonCms\Core\Helpers\ResetHelper;
use Photon\PhotonCms\Core\Helpers\LicenseKeyHelper;
use Photon\PhotonCms\Core\Entities\DynamicModuleModel\DynamicModuleModelHelper;
use Photon\PhotonCms\Core\Entities\Model\ModelTemplateFactory;
use Photon\PhotonCms\Core\Entities\NativeClass\NativeClassRepository;
use Photon\PhotonCms\Core\Entities\NativeClass\NativeClassCompiler;
use Photon\PhotonCms\Core\Entities\NativeClass\Contracts\NativeClassGatewayInterface;
use Illuminate\Support\Facades\Cache;

class PhotonController extends Controller
{

    /**
     * @var SeedRepository
     */
    private $seedRepository;

    /**
     * @var SeedGateway
     */
    private $seedGateway;

    /**
     * @var ResponseRepository
     */
    private $responseRepository;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     *
     * @var ModuleRepository
     */
    private $moduleRepository;

    /**
     *
     * @var ModuleGatewayInterface
     */
    private $moduleGateway;

    /**
     *
     * @var ModelRepository
     */
    private $modelRepository;

    /**
     *
     * @var ModelCompiler
     */
    private $modelCompiler;

    /**
     *
     * @var ModelGatewayInterface
     */
    private $modelGateway;

    /**
     * @var DynamicModuleMigrationRepository
     */
    private $migrationRepository;

    /**
     * @var MigrationGatewayInterface
     */
    private $migrationGateway;

    /**
     *
     * @var MigrationCompiler
     */
    private $migrationCompiler;

    /**
     * @var DynamicModuleLibrary
     */
    private $dynamicModuleLibrary;

    /**
     * @var DynamicModuleRepository
     */
    private $dynamicModuleRepository;

    /**
     * @var NativeClassRepository
     */
    private $classRepository;

    /**
     * @var NativeClassGatewayInterface
     */
    private $classGateway;

    /**
     * @var NativeClassCompiler
     */
    private $classCompiler;

    /**
     * Constructor.
     *
     * @param ResponseRepository $responseRepository
     * @param SeedRepository $seedRepository
     * @param SeedGateway $seedGateway
     * @param Filesystem $filesystem
     * @param ModuleRepository $moduleRepository
     * @param ModuleGatewayInterface $moduleGateway
     * @param ModelRepository $modelRepository
     * @param ModelCompiler $modelCompiler
     * @param ModelGatewayInterface $modelGateway
     * @param DynamicModuleMigrationRepository $migrationRepository
     * @param MigrationGatewayInterface $migrationGateway
     * @param MigrationCompiler $migrationCompiler
     * @param DynamicModuleLibrary $dynamicModuleLibrary
     * @param DynamicModuleRepository $dynamicModuleRepository
     * @param DynamicModuleGateway $dynamicModuleGateway
     * @param NativeClassRepository $classRepository
     * @param NativeClassGatewayInterface $classGateway
     * @param NativeClassCompiler $classCompiler
     */
    public function __construct(
        ResponseRepository $responseRepository,
        SeedRepository $seedRepository,
        SeedGateway $seedGateway,
        Filesystem $filesystem,
        ModuleRepository $moduleRepository,
        ModuleGatewayInterface $moduleGateway,
        ModelRepository $modelRepository,
        ModelCompiler $modelCompiler,
        ModelGatewayInterface $modelGateway,
        DynamicModuleMigrationRepository $migrationRepository,
        MigrationGatewayInterface $migrationGateway,
        MigrationCompiler $migrationCompiler,
        DynamicModuleLibrary $dynamicModuleLibrary,
        DynamicModuleRepository $dynamicModuleRepository,
        NativeClassRepository $classRepository,
        NativeClassGatewayInterface $classGateway,
        NativeClassCompiler $classCompiler
    ) {
        $this->seedRepository          = $seedRepository;
        $this->seedGateway             = $seedGateway;
        $this->responseRepository      = $responseRepository;
        $this->filesystem              = $filesystem;
        $this->moduleRepository        = $moduleRepository;
        $this->moduleGateway           = $moduleGateway;
        $this->modelRepository         = $modelRepository;
        $this->modelCompiler           = $modelCompiler;
        $this->modelGateway            = $modelGateway;
        $this->migrationRepository     = $migrationRepository;
        $this->migrationGateway        = $migrationGateway;
        $this->migrationCompiler       = $migrationCompiler;
        $this->dynamicModuleLibrary    = $dynamicModuleLibrary;
        $this->dynamicModuleRepository = $dynamicModuleRepository;
        $this->classGateway            = $classGateway;
        $this->classRepository         = $classRepository;
        $this->classCompiler           = $classCompiler;
    }

    /**
     * Clears all dynamic models, dynamic migrations and DB tables, rebuilds seeders and starts the migration from scratch.
     *
     * @return Illuminate\Http\Response
     * @throws PhotonException
     */
    public function hardReset()
    {
        if (!env('CAN_RESET_PHOTON') || !\App::environment('local', 'staging', 'development', 'testing')) {
            throw new PhotonException('PHOTON_RESET_FORBIDDEN');
        }

        if(config("photon.use_photon_cache")) {
            Cache::tags(env("APPLICATION_URL"))->flush();
        }
        Cache::flush("all_permissions");

        ResetHelper::removeLogFiles();
        ResetHelper::deleteModels();
        ResetHelper::deleteModuleExtenders();
        ResetHelper::deleteAssets();
        ResetHelper::deleteMigrations();
        ResetHelper::deleteTables();
        ResetHelper::cleanDirectories();
        ResetHelper::rebuildDefaultModuleExtenders();
        ResetHelper::runMigrations();
        ResetHelper::seedInitialCore();
        ResetHelper::rebuildAndRunMigrations();
        ResetHelper::seedInitialValues();
        ResetHelper::rebuildModels();
        DatabaseHelper::rebuildSeeders();
        ResetHelper::updatePasswordCreationTime();

        return $this->responseRepository->make('PHOTON_HARD_RESET_SUCCESS');
    }

    /**
     * Resets the application to the default except that it doesn't remove extenders.
     * When using hard reset, you can set an additional core seed for the project to have your project modules preserved while
     * reseting to initial state. This cannot be done with extenders so this is why this method is handy.
     *
     * @return Illuminate\Http\Response
     * @throws PhotonException
     */
    public function softReset()
    {
        if (!env('CAN_RESET_PHOTON') || !\App::environment('local', 'staging', 'development', 'testing')) {
            throw new PhotonException('PHOTON_RESET_FORBIDDEN');
        }

        if(config("photon.use_photon_cache")) {
            Cache::tags(env("APPLICATION_URL"))->flush();
        }
        Cache::flush("all_permissions");

        ResetHelper::removeLogFiles();
        ResetHelper::deleteModels();
        ResetHelper::deleteAssets();
        ResetHelper::deleteMigrations();
        ResetHelper::deleteTables();
        ResetHelper::cleanDirectories();
        ResetHelper::runMigrations();
        ResetHelper::seedInitialCore();
        ResetHelper::rebuildAndRunMigrations();
        ResetHelper::seedInitialValues();
        ResetHelper::rebuildModels();
        DatabaseHelper::rebuildSeeders();
        ResetHelper::updatePasswordCreationTime();

        return $this->responseRepository->make('PHOTON_SOFT_RESET_SUCCESS');
    }

    /**
     * Synchronizes the current installation state with the one received from SCM.
     *
     * @return \Illuminate\Http\Response
     */
    public function sync()
    {
        $maxExecutionTimeSetup = ini_get('max_execution_time');
        set_time_limit(0);

        if(config("photon.use_photon_cache")) {
            Cache::tags(env("APPLICATION_URL"))->flush();
        }
        Cache::flush("all_permissions");

        $modules = $this->moduleRepository->getAll($this->moduleGateway);

        $backedUpTableNames = [];
        $backedUpPivotTables = [];
        foreach ($modules as $module) {
            $gateway = $this->dynamicModuleLibrary->getGatewayInstanceByTableName($module->table_name);
            $this->dynamicModuleRepository->backupModuleData($gateway);
            $backedUpTableNames[] = $module->table_name;

            $modelRelations = ModelRelationFactory::makeMultipleFromFields($module->fields);
            foreach ($modelRelations as $relation) {
                if(!$relation->requiresPivot()) {
                    continue;
                }

                $gateway = $this->dynamicModuleLibrary->getGatewayInstanceByTableName($module->table_name);
                $this->dynamicModuleRepository->backupPivotTableData($relation, $gateway);

                $backedUpPivotTables[$module->table_name][] = $relation->pivotTable;
            }
        }

        ResetHelper::deleteModels();
        ResetHelper::deleteMigrations();
        ResetHelper::deleteTables();
        ResetHelper::runMigrations(); // Runs photon base migrations
        DatabaseHelper::seedTablesData(config('photon.photon_sync_clear_tables'), true);
        ResetHelper::rebuildAndRunMigrations(); // Re/builds all photon module migrations and runs them
        ResetHelper::rebuildModels();

        $modules = $this->moduleRepository->getAll($this->moduleGateway);
        foreach ($modules as $module) {
            $gateway = $this->dynamicModuleLibrary->getGatewayInstanceByTableName($module->table_name);
            $this->dynamicModuleRepository->restoreModuleData($gateway);

            $modelRelations = ModelRelationFactory::makeMultipleFromFields($module->fields);
            foreach ($modelRelations as $relation) {
                if(!$relation->requiresPivot()) {
                    continue;
                }
                
                $this->dynamicModuleRepository->restorePivotTableData($relation->pivotTable, $gateway);
            }
        }

        set_time_limit($maxExecutionTimeSetup);

        return $this->responseRepository->make('PHOTON_SYNC_SUCCESS');
    }

    /**
     * Reverts data to the last sync state.
     *
     * @return \Illuminate\Http\Response
     */
    public function revertToSync()
    {
        $maxExecutionTimeSetup = ini_get('max_execution_time');
        set_time_limit(0);

        if(config("photon.use_photon_cache")) {
            Cache::tags(env("APPLICATION_URL"))->flush();
        }
        Cache::flush("all_permissions");

        $modules = $this->moduleRepository->getAll($this->moduleGateway);

        foreach ($modules as $module) {
            $gateway = $this->dynamicModuleLibrary->getGatewayInstanceByTableName($module->table_name);
            $this->dynamicModuleRepository->restoreModuleData($gateway);

            $modelRelations = ModelRelationFactory::makeMultipleFromFields($module->fields);
            foreach ($modelRelations as $relation) {
                if(!$relation->requiresPivot()) {
                    continue;
                }

                $this->dynamicModuleRepository->restorePivotTableData($relation->pivotTable, $gateway);
            }
        }

        set_time_limit($maxExecutionTimeSetup);

        return $this->responseRepository->make('PHOTON_REVERT_TO_SYNC_SUCCESS');
    }

    /**
     * Reverts specific module data to the last sync state.
     *
     * @param string $tableName
     * @return \Illuminate\Http\Response
     */
    public function revertModuleToSync($tableName = null)
    {
        $maxExecutionTimeSetup = ini_get('max_execution_time');
        set_time_limit(0);

        $gateway = $this->dynamicModuleLibrary->getGatewayInstanceByTableName($tableName);
        $this->dynamicModuleRepository->restoreModuleData($gateway);
        
        $modelRelations = ModelRelationFactory::makeMultipleFromFields($module->fields);
        foreach ($modelRelations as $relation) {
            if(!$relation->requiresPivot()) {
                continue;
            }

            $this->dynamicModuleRepository->restorePivotTableData($relation->pivotTable, $gateway);
        }

        if(config("photon.use_photon_cache")) {
            Cache::tags([$tableName])->flush();
        }
        Cache::flush("all_permissions");

        set_time_limit($maxExecutionTimeSetup);

        return $this->responseRepository->make('PHOTON_REVERT_TO_SYNC_SUCCESS');
    }

    /**
     * Rebuilds a model file for the specified module.
     *
     * @param string $tableName
     * @return \Illuminate\Http\Response
     * @throws PhotonException
     */
    public function rebuildModuleModel($tableName)
    {
        $module = $this->moduleRepository->findModuleByTableName($tableName, $this->moduleGateway);

        if (is_null($module)) {
            throw new PhotonException('MODULE_NOT_FOUND', ['table_name' => $tableName]);
        }
        $this->modelRepository->rebuildModel($module, $this->modelCompiler, $this->modelGateway);

        return $this->responseRepository->make('PHOTON_REBUILD_MODULE_MODEL_SUCCESS');
    }

    /**
     * Rebuilds an extender for the specified module.
     *
     * @param string $tableName
     * @return \Illuminate\Http\Response
     * @throws PhotonException
     */
    public function rebuildModuleExtender($tableName)
    {
        $module = $this->moduleRepository->findModuleByTableName($tableName, $this->moduleGateway);

        if (is_null($module)) {
            throw new PhotonException('MODULE_NOT_FOUND', ['table_name' => $tableName]);
        }

        $extenderName = DynamicModuleModelHelper::generateModelExtenderNameFromString($module->name);
        $extenderTemplate = ModelTemplateFactory::makeDynamicModuleExtensionTemplate();
        $extenderTemplate->setClassName($extenderName);
        $this->classRepository->saveFromTemplate($extenderTemplate, $this->classCompiler, $this->classGateway);

        return $this->responseRepository->make('PHOTON_REBUILD_MODULE_EXTENDER_SUCCESS');
    }

    /**
     * Pings photon haven and generates license.
     *
     * @return \Illuminate\Http\Response
     * @throws PhotonException
     */
    public function pingHome()
    {
        $force = \Request::get("force");

        // check cache
        if(Cache::has('photon-license') && !$force) {
            $validKey = Cache::get('photon-license');
            return $this->responseRepository->make($validKey['message'], $validKey['body']);
        }

        // check if license key exist
        $key = LicenseKeyHelper::checkLicenseKey();

        // ping home
        $validKey = LicenseKeyHelper::pingHome($key);

        $currentVersion = \ComposerBump::getVersion();
        $validKey['body']['current_version'] = $currentVersion;
        $validKey['body']['upgrade_available'] = $validKey['body']['current_version'] == $validKey['body']['newest_version'] ? false : true;

        // store data in cache
        Cache::put('photon-license', $validKey, 60);

        // store license key if it does not exist
        if(!$key) 
            LicenseKeyHelper::storeLiceseKey($validKey['body']['license_key']);
        
        // return response
        return $this->responseRepository->make($validKey['message'], $validKey['body']);
    }
}
