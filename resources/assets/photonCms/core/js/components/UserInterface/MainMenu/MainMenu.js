import {
    mapGetters,
    mapActions
} from 'vuex';

import { showIntro } from '_/helpers/guidedTours';

import { store } from '_/vuex/store';

import { router } from '_/router/router';

import { storage } from '_/services/storage';

import { config } from '_/config/config';

import { userHasRole } from '_/vuex/actions/userActions';

export default {
    /**
     * Set the component data
     *
     * @type  {Object}
     */
    data: function() {
        return {
            /**
             * License Status object
             *
             * @type  {object}
             */
            licenseStatus: storage.get('licenseStatus', true),
        };
    },

    /**
     * Set the computed variables
     *
     * @type {object}
     */
    computed: {
        ...mapGetters({
            assetsManager: 'assets/assets',
            photonModules: 'photonModule/photonModules',
            ui: 'ui/ui',
            user: 'user/user'
        }),

        /**
         * Returns the photon configuration object
         *
         * @return  {object}
         */
        photonConfig () {
            return config;
        },
    },

    /**
     * Set the methods
     *
     * @type  {object}
     */
    methods: {
        resetMainMenuOnResize: function() { // Menu on rezise reset function
            $('nav.main-menu').removeClass('expanded');

            $('nav.main-menu .active').removeClass('active');

            $('.main-menu-access').removeClass('active');
        },

        closeMenu () {
            if (!Modernizr.mq('(max-width: 480px)')) {
                return;
            }

            if ($('.touch nav.main-menu').is('.expanded')) {
                $('.main-menu-access').removeClass('active');

                $('nav.main-menu').find('.active').removeClass('active');

                $('.touch nav.main-menu').removeClass('expanded');

                $('html, body').animate({
                    scrollTop: 0
                }, 300, 'swing');
            }
            $('nav.user-menu > section .active').removeClass('active');

            $('.nav-view').fadeOut(30);
        },

        /**
         * Menu events setter (mostly as inherited from Proton UI code)
         *
         * @return  {void}
         */
        events: function() {
            const self = this;

            /**
             * Nav expansion for touch devices
             *
             * @return  {boolean}
             */
            $(document).on('click', 'nav.main-menu', function() {
                if (!Modernizr.mq('(max-width: 480px)')) {
                    return;
                }

                $(this).addClass('expanded');

                return false;
            });

            /**
             * Main menu activator on mobile
             *
             * @return  {boolean}
             */
            $(document).on('click', '.main-menu-access', function() {
                $('nav.user-menu > section .active').removeClass('active');

                $('.nav-view').fadeOut(30);

                if ($(this).is('.active')) {
                    $(this).removeClass('active');

                    $('nav.main-menu').removeClass('expanded');
                } else {
                    $('nav.main-menu').addClass('expanded');

                    $(this).addClass('active');
                }

                return false;
            });

            /**
             * Nav retraction for touch devices
             *
             * @return  {void}
             */
            $(document).on('click', 'body', function() {
                if (!Modernizr.mq('(max-width: 480px)')) {
                    return;
                }

                self.closeMenu();
            });

            $(this.$el).on('click', 'nav.user-menu', function(event) {
                if (!Modernizr.mq('(max-width: 480px)')) {
                    return;
                }

                event.stopPropagation();
            });

            /**
             * Nav item activation for touch devices
             *
             * @return  {void}
             */
            $(this.$el).on('click', 'ul li', function(event) {
                if (!Modernizr.mq('(max-width: 480px)')) {
                    return;
                }

                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');
                } else {
                    $('nav.main-menu').find('li').removeClass('active');

                    $(this).addClass('active');
                }

                event.stopPropagation();
            });
        },

        /**
         * Clears set menu events
         *
             * @return  {void}
         */
        eventsOff: function() {
            $(document).off('click', 'nav.main-menu');

            $(document).off('click', '.main-menu-access');

            $(document).off('click', 'body');

            $(this.$el).off('click', 'nav.user-menu');

            $(this.$el).off('click', 'ul li');
        },

        ...mapActions('user', [ // Map actions from user module namespace
            'impersonateUser',
            'logout'
        ]),

        ...mapActions('photonModule', [ // Map actions from photonModuleActions module namespace
            'getPhotonModules'
        ]),

        ...mapActions('ui', [ // Map actions from photonModuleActions module namespace
            'getMainMenu'
        ]),

        /**
         * Shows modal window
         *
         * @return  {void}
         */
        openAssetsManager({ assetId = null } = {}) {
            store.dispatch(
                'assets/initializeState', {
                    multiple: false,
                    value: [],
                });

            store.dispatch('assets/assetsManagerVisible', { value: true });

            store.dispatch(
                'assets/initializeState', {
                    multiple: false,
                    value: assetId ? assetId : [],
                })
                .then(() => {
                    store.dispatch('assets/assetsManagerVisible', { value: true })
                        .then(() => {
                            if (!assetId) {
                                return;
                            }

                            const filter = {
                                id: {
                                    equal: assetId
                                }
                            };

                            store.dispatch('assets/setFilterObject', { value: filter })
                                .then(() => {
                                    store.dispatch(
                                        'assets/getSelectedAssets', {
                                            selectedAssetsIds: this.assetsManager.selectedAssetsIds,
                                        })
                                        .then(() => {
                                            if(Array.isArray(this.assetsManager.selectedAssets)
                                                && this.assetsManager.selectedAssets.length > 0) {
                                                store.dispatch(
                                                    'assets/selectAsset', {
                                                        asset: this.assetsManager.selectedAssets[0],
                                                    })
                                                    .then(() => {
                                                        store.dispatch('assets/toggleAssetEntryUpdated');

                                                        $('#accordion-asset-details-label').click();
                                                    })
                                            }
                                        })

                                    this.$emit('submitSearch');
                                });
                    });
                });
        },

        /**
         * Checks if a user has given role
         *
         * @param   {string}  role
         * @return  {bool}
         */
        userHasRole,

        /**
         * Starts the given guided tour
         *
         * @param   {string}  uri
         * @return  {void}
         */
        startGuidedTour (uriSegment, storageKey) {
            const uri = '/' + uriSegment;

            storage.remove('intro-' + storageKey);

            if (this.$route.path == uri) {
                showIntro[storageKey](this);
            } else {
                router.push(uri);
            }
        }
    },

    /**
     * Call a mounted hook
     *
     * @type {function}
     */
    mounted: function() {
        this.$nextTick(function() {
            if(this.$route.name === 'asset-manager' && this.$route.params.assetId > 0) {
                this.openAssetsManager({ assetId: this.$route.params.assetId });
            }

            this.getPhotonModules();

            this.getMainMenu();

            this.events(); // Set the events

            this.resetMainMenuOnResize(); // Run menu reset
        });
    },

    /**
     * Call a beforeDestroy hook
     *
     * @type {function}
     */
    beforeDestroy: function() {
        this.eventsOff(); // Unbind events
    },

    /**
     * Set the watched expressions
     *
     * @type {Object}
     */
    watch: {
        /**
         * Watching the route change
         *
         * @return  {void}
         */
        $route (newEntry, oldEntry) {
            if(newEntry.params.moduleTableName !== oldEntry.params.moduleTableName) {
                this.closeMenu();
            }

            if(this.$route.name === 'asset-manager' && newEntry.params.assetId !== oldEntry.params.assetId) {
                this.openAssetsManager({ assetId: this.$route.params.assetId });
            }
        },

        'ui.bodyWidth': function() {
            this.resetMainMenuOnResize(); // Run menu reset on resize
        }
    }
};
