<div class="data-scrollbar" data-scroll="1">
    <nav class="iq-sidebar-menu">
        <ul id="iq-sidebar-toggle" class="iq-menu">

            @can('view-dashboard')
            <li class="{{ (strpos(Route::currentRouteName(), 'home') === 0) ? 'active' : ''}}">
                <a href="{{ route('home') }}" class="svg-icon">
                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="20" height="20"  viewBox="0 0 60.000000 60.000000"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                        <g transform="translate(0.000000,60.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                            <path d="M6 554 c-8 -20 -8 -488 0 -508 3 -9 9 -16 13 -16 3 0 5 117 3 260
                        l-3 260 281 0 280 0 0 -249 c0 -218 -2 -250 -16 -255 -9 -3 -112 -6 -230 -6
                        l-214 0 0 230 c0 149 -4 230 -10 230 -7 0 -9 -81 -8 -237 l3 -238 238 0 c200
                        0 239 2 247 15 11 16 14 488 4 514 -5 14 -41 16 -294 16 -253 0 -289 -2 -294
                        -16z" />
                            <path d="M164 497 c-3 -8 -4 -41 -2 -73 l3 -59 95 0 95 0 0 70 0 70 -93 3
                        c-72 2 -94 0 -98 -11z m176 -62 l0 -55 -80 0 -80 0 0 55 0 55 80 0 80 0 0 -55z" />
                            <path d="M205 430 c3 -5 19 -10 36 -10 16 0 29 5 29 10 0 6 -16 10 -36 10 -21
                        0 -33 -4 -29 -10z" />
                            <path d="M295 430 c4 -6 11 -8 16 -5 14 9 11 15 -7 15 -8 0 -12 -5 -9 -10z" />
                            <path d="M422 498 c-12 -12 -16 -95 -6 -122 5 -12 20 -16 60 -16 61 0 74 13
                        74 75 0 60 -14 75 -68 75 -27 0 -53 -5 -60 -12z m98 -63 l0 -55 -45 0 -45 0 0
                        48 c0 27 3 52 7 55 3 4 24 7 45 7 l38 0 0 -55z" />
                            <path d="M40 490 c0 -5 6 -10 14 -10 8 0 18 5 21 10 3 6 -3 10 -14 10 -12 0
                        -21 -4 -21 -10z" />
                            <path d="M40 430 c0 -5 9 -10 21 -10 11 0 17 5 14 10 -3 6 -13 10 -21 10 -8 0
                        -14 -4 -14 -10z" />
                            <path d="M45 370 c-3 -5 3 -10 15 -10 12 0 18 5 15 10 -3 6 -10 10 -15 10 -5
                        0 -12 -4 -15 -10z" />
                            <path d="M167 303 c-11 -10 -8 -143 3 -143 6 0 10 28 10 65 l0 65 170 0 170 0
                        0 -90 0 -90 -160 0 c-92 0 -160 4 -160 9 0 5 7 17 16 25 11 12 20 13 34 6 31
                        -16 38 -12 76 41 l37 50 33 -32 33 -32 38 24 c30 18 34 25 20 27 -10 2 -26 -3
                        -36 -12 -16 -15 -20 -14 -49 14 -45 43 -43 44 -105 -44 -16 -23 -20 -24 -43
                        -14 -24 11 -29 9 -55 -20 -54 -60 -50 -62 151 -62 209 0 200 -5 200 110 0 115
                        9 110 -199 110 -98 0 -181 -3 -184 -7z" />
                            <path d="M44 109 c-8 -14 11 -33 25 -25 6 4 11 11 11 16 0 13 -29 20 -36 9z" />
                        </g>
                    </svg>
                    <span class="ml-4"> {{__('nav.dashboards')}}</span>
                </a>
            </li>
            @endcan

            @can('view-installer-card')
                <li class="{{ (strpos(Route::currentRouteName(), 'installercards.index') === 0) || (strpos(Route::currentRouteName(), 'installercards.edit') === 0)  ? 'active' : ''}}">
                    <a href="#new_installercard" class="text-wrap collapsed" data-toggle="collapse" aria-expanded="false">
                        {{-- <i class="fas fa-gifts"></i> --}}
                        <i class="fas fa-id-card"></i>
                        <span class="ml-4 text-wrap">{{__('nav.installer_cards')}}</span>
                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="10 15 15 20 20 15"></polyline>
                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                        </svg>

                    </a>
                    <ul id="new_installercard" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                        @can('create-installer-card')
                        <li class="{{ (strpos(Route::currentRouteName(), 'installercards.create') === 0)  ? 'active' : ''}}">
                            <a href="{{route('installercards.create')}}" class="svg-icon">
                                <i class="las la-minus"></i><span>{{__('nav.create_installer_cards')}}</span>
                             </a>
                        </li>
                        @endcan
                        @can('register-installer-card')
                        <li class="{{ (strpos(Route::currentRouteName(), 'installercards.register') === 0)  ? 'active' : ''}}">
                            <a href="{{route('installercards.register')}}" class="svg-icon">
                                <i class="las la-minus"></i><span>{{__('nav.register_installer_cards')}}</span>
                             </a>
                        </li>
                        @endcan
                        @can('view-installer-card')
                        <li class="{{ (strpos(Route::currentRouteName(), 'installercards.index') === 0)  ? 'active' : ''}}">
                            <a href="{{route('installercards.index')}}">
                                <i class="las la-minus"></i><span>{{__('nav.installer_card_list')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('view-installer-card')
                        <li class="{{ (strpos(Route::currentRouteName(), 'saleamountchecks.index') === 0)  ? 'active' : ''}}">
                            <a href="{{route('saleamountchecks.index')}}">
                                <i class="las la-minus"></i><span>{{__('nav.sale_amount_checks')}}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endcan


            {{-- @can('view-home-owner') --}}
                <li class="{{ (strpos(Route::currentRouteName(), 'homeowners.index') === 0) || (strpos(Route::currentRouteName(), 'homeowners.edit') === 0)  ? 'active' : ''}}">
                    <a href="#new_homeowner" class="text-wrap collapsed" data-toggle="collapse" aria-expanded="false">
                        {{-- <i class="fas fa-gifts"></i> --}}
                        <i class="fas fa-id-card"></i>
                        <span class="ml-4 text-wrap">{{__('nav.home_owners')}}</span>
                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="10 15 15 20 20 15"></polyline>
                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                        </svg>

                    </a>
                    <ul id="new_homeowner" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                        {{-- @can('create-installer-card') --}}
                        <li class="{{ (strpos(Route::currentRouteName(), 'homeowners.create') === 0)  ? 'active' : ''}}">
                            <a href="{{route('homeowners.create')}}" class="svg-icon">
                                <i class="las la-minus"></i><span>{{__('nav.create_home_owners')}}</span>
                             </a>
                        </li>
                        {{-- @endcan --}}
                        {{-- @can('view-installer-card') --}}
                        <li class="{{ (strpos(Route::currentRouteName(), 'homeowners.index') === 0)  ? 'active' : ''}}">
                            <a href="{{route('homeowners.index')}}">
                                <i class="las la-minus"></i><span>{{__('nav.installer_card_list')}}</span>
                            </a>
                        </li>
                        {{-- @endcan --}}
                    </ul>
                </li>
            {{-- @endcan --}}


            {{-- @can('view-home-owner') --}}
            <li class="{{ (strpos(Route::currentRouteName(), 'cardnumbergenerators.index') === 0)  ? 'active' : ''}}">
                <a href="#new_cardnumbergenerator" class="text-wrap collapsed" data-toggle="collapse" aria-expanded="false">
                    {{-- <i class="fas fa-gifts"></i> --}}
                    <i class="fas fa-id-card"></i>
                    <span class="ml-4 text-wrap">{{__('nav.card_number_generators')}}</span>
                    <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="10 15 15 20 20 15"></polyline>
                        <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                    </svg>

                </a>
                <ul id="new_cardnumbergenerator" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                    {{-- @can('create-installer-card') --}}
                    <li class="{{ (strpos(Route::currentRouteName(), 'cardnumbergenerators.create') === 0)  ? 'active' : ''}}">
                        <a href="{{route('cardnumbergenerators.create')}}" class="svg-icon">
                            <i class="las la-minus"></i><span>{{__('nav.create_card_number_generators')}}</span>
                         </a>
                    </li>
                    {{-- @endcan --}}
                    {{-- @can('view-installer-card') --}}
                    <li class="{{ (strpos(Route::currentRouteName(), 'cardnumbergenerators.index') === 0)  ? 'active' : ''}}">
                        <a href="{{route('cardnumbergenerators.index')}}">
                            <i class="las la-minus"></i><span>{{__('nav.card_number_generators')}}</span>
                        </a>
                    </li>
                    {{-- @endcan --}}
                </ul>
            </li>
            {{-- @endcan --}}

            @can('create-promotion')
            <li class=" ">
                <a href="#new_pointpromotion" class="collapsed" data-toggle="collapse" aria-expanded="false">
                    {{-- <i class="fas fa-gifts"></i> --}}
                   <svg xmlns="http://www.w3.org/2000/svg" viewBox="-0.5 -0.5 24 24" id="Sack-Dollar--Streamline-Font-Awesome" height="24" width="24"><desc>Sack Dollar Streamline Icon: https://streamlinehq.com</desc><!--! Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2024 Fonticons, Inc.--><path d="M14.375 4.3125H8.625l-2.129296875 -3.1939453125c-0.31894531249999997 -0.4806640625 0.0224609375 -1.1185546874999999 0.5974609375000001 -1.1185546874999999h8.813671874999999c0.5750000000000001 0 0.91640625 0.6378906249999999 0.5974609375000001 1.1185546874999999L14.375 4.3125zm-5.75 1.4375h5.75c0.17070312499999998 0.1123046875 0.36386718749999997 0.23808593749999998 0.583984375 0.37734375000000003 2.5470703125000003 1.6306640625 8.041015625 5.1435546875 8.041015625 12.56015625 0 2.380859375 -1.931640625 4.3125 -4.3125 4.3125H4.3125c-2.380859375 0 -4.3125 -1.931640625 -4.3125 -4.3125 0 -7.4166015624999995 5.4939453125 -10.929492187500001 8.041015625 -12.56015625 0.21562499999999998 -0.1392578125 0.41328125 -0.26503906250000003 0.583984375 -0.37734375000000003zm3.7734375 3.953125c0 -0.494140625 -0.404296875 -0.8984375 -0.8984375 -0.8984375s-0.8984375 0.404296875 -0.8984375 0.8984375v0.62890625c-0.34140624999999997 0.0763671875 -0.6828124999999999 0.19765625 -0.9972656249999999 0.3818359375 -0.6244140625 0.3728515625 -1.1634765624999999 1.02421875 -1.158984375 1.9720703124999999 0.0044921875000000005 0.9119140625000001 0.5390625 1.4869140625000001 1.1095703125 1.8283203125 0.494140625 0.29648437499999997 1.1095703125 0.48515625 1.5992187500000001 0.62890625l0.0763671875 0.0224609375c0.566015625 0.17070312499999998 0.9792968750000001 0.30546875 1.2578125 0.4806640625 0.2291015625 0.14375000000000002 0.260546875 0.242578125 0.26503906250000003 0.368359375 0.0044921875000000005 0.224609375 -0.080859375 0.359375 -0.26503906250000003 0.4716796875 -0.224609375 0.1392578125 -0.5794921875 0.224609375 -0.961328125 0.21113281250000002 -0.49863281249999997 -0.017968750000000002 -0.9658203125 -0.1751953125 -1.5767578125000001 -0.3818359375 -0.1033203125 -0.035937500000000004 -0.21113281250000002 -0.07187500000000001 -0.3234375 -0.10781249999999999 -0.4716796875 -0.1572265625 -0.9792968750000001 0.098828125 -1.1365234375 0.566015625s0.098828125 0.9792968750000001 0.566015625 1.1365234375c0.08535156249999999 0.026953124999999998 0.1796875 0.058398437500000004 0.27402343749999997 0.09433593750000001 0.3728515625 0.1302734375 0.8041015625 0.278515625 1.266796875 0.37734375000000003V19.046875c0 0.494140625 0.404296875 0.8984375 0.8984375 0.8984375s0.8984375 -0.404296875 0.8984375 -0.8984375v-0.6199218750000001c0.359375 -0.0763671875 0.71875 -0.2021484375 1.0421875 -0.404296875 0.6423828125000001 -0.39980468750000003 1.1275390625000001 -1.0826171875000001 1.1140625 -2.021484375 -0.013476562499999999 -0.9119140625000001 -0.5255859374999999 -1.5003906249999999 -1.1050781250000001 -1.8687500000000001 -0.5166015625 -0.3234375 -1.1634765624999999 -0.52109375 -1.6666015625000001 -0.673828125l-0.031445312499999996 -0.008984375000000001c-0.5750000000000001 -0.1751953125 -0.9837890625 -0.3009765625 -1.2712890625 -0.4716796875 -0.23359375000000002 -0.1392578125 -0.23808593749999998 -0.2201171875 -0.23808593749999998 -0.3009765625 0 -0.16621093750000002 0.06289062499999999 -0.2919921875 0.278515625 -0.4177734375 0.242578125 -0.14375000000000002 0.6109375 -0.2291015625 0.9658203125 -0.224609375 0.43124999999999997 0.0044921875000000005 0.9074218749999999 0.098828125 1.4015625 0.23359375000000002 0.4806640625 0.12578124999999998 0.9703125 -0.1572265625 1.1005859375 -0.6378906249999999s-0.1572265625 -0.9703125 -0.6378906249999999 -1.1005859375c-0.2919921875 -0.0763671875 -0.6154296874999999 -0.152734375 -0.9478515625 -0.21113281250000002V9.703125z" stroke-width="1" fill="#6c757d"></path></svg>
                    <span class="ml-4">{{__('nav.point_promotions')}}</span>
                    <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="10 15 15 20 20 15"></polyline>
                        <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                    </svg>

                </a>
                <ul id="new_pointpromotion" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                    @can('create-promotion')
                    <li  class="{{ (strpos(Route::currentRouteName(), 'pointpromos.create') === 0)  ? 'active' : ''}}">
                        <a href="{{route('pointpromos.create')}}">
                            <i class="las la-minus"></i><span>{{__('nav.create_point_promotions')}}</span>
                        </a>
                    </li>
                    @endcan
                    @can('view-promotion')
                    <li class="{{ (strpos(Route::currentRouteName(), 'pointpromos.index') === 0)  ? 'active' : ''}}">
                        <a href="{{route('pointpromos.index')}}">
                            <i class="las la-minus"></i><span>{{__('nav.point_promotion_list')}}</span>
                        </a>
                    </li>
                    @endcan

                </ul>
            </li>
            @endcan


            @can('check-installer-card')
            <li class="{{ (strpos(Route::currentRouteName(), 'installercards.checking') === 0) || (strpos(Route::currentRouteName(), 'installercardpoints.detail') === 0) ? 'active' : ''}}">
                <a href="{{route('installercards.checking')}}" class="svg-icon text-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="-0.94 -0.94 20 20" stroke="#000000" aria-hidden="true" id="Shield-Check--Streamline-Heroicons-Outline" height="20" width="20"><desc>Shield Check Streamline Icon: https://streamlinehq.com</desc><path stroke-linecap="round" stroke-linejoin="round" d="M6.795 9.62625 8.49375 11.325 11.325 7.36125m-2.265 -5.31218A9.029045 9.029045 0 0 1 2.71649 4.53 9.05245 9.05245 0 0 0 2.265 7.360495c0 4.22196 2.88712 7.768949999999999 6.795 8.775364999999999 3.90788 -1.00566 6.795 -4.55265 6.795 -8.77461 0 -0.9890500000000001 -0.15855 -1.941105 -0.45149 -2.832005h-0.11476c-2.41298 0 -4.6055 -0.94224 -6.22875 -2.480175Z" stroke-width="1.88" ></path></svg>
                    <span class="ml-4 text-wrap">{{__('nav.installer_card_checking')}}</span>
                </a>
            </li>
            @endcan


            @can('view-collection-transaction')
            <li class="{{ (strpos(Route::currentRouteName(), 'collectiontransactions.show') === 0) || (strpos(Route::currentRouteName(), 'returnbanners.show') === 0) ? 'active' : ''}}">
                <a href="#collectiontransaction" class="collapsed" data-toggle="collapse" aria-expanded="false">
                    <i class="fas fa-receipt"></i>
                    <span class="ml-4">{{__('nav.collection_transactions')}}</span>
                    <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="10 15 15 20 20 15"></polyline>
                        <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                    </svg>
                </a>
                <ul id="collectiontransaction" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                    <li class="{{ (strpos(Route::currentRouteName(), 'collectiontransactions.index') === 0)  ? 'active' : ''}}">
                        <a href="{{route('collectiontransactions.index')}}" class="svg-icon">
                            <i class="las la-minus"></i><span> {{__('nav.collection_transaction_list')}}</span>
                        </a>
                    </li>
                    <li class="{{ (strpos(Route::currentRouteName(), 'collectiontransactiondeletelogs.index') === 0)  ? 'active' : ''}}">
                        <a href="{{route('collectiontransactiondeletelogs.index')}}">
                            <i class="las la-minus"></i><span>{{__('nav.collection_transaction_delete_logs')}}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan


            @can('notified-redemption-transaction')
            <li class="{{ (strpos(Route::currentRouteName(), 'redemptiontransactions.approvalnotifications') === 0) ? 'active' : ''}}">
                <a href="{{route('redemptiontransactions.approvalnotifications')}}" class="svg-icon">
                    <i class="fas fa-comments-dollar fa-2x"></i>
                    {{-- <i class="fas fa-money-check-alt"></i> --}}
                    <span class="ml-4">{{__('nav.redemption_requests')}}</span>
                </a>
                @if(Auth::user()->unreadNotifications->count() > 0)
                <span class="approvalnotibadges">{{ Auth::user()->unreadNotifications->count() }} +</span>
                @endif
            </li>
            @endcan

            @can('view-redemption-transaction')
            <li class="{{ (strpos(Route::currentRouteName(), 'redemptiontransactions.index') === 0) || (strpos(Route::currentRouteName(), 'redemptiontransactions.show') === 0) ? 'active' : ''}}">
                <a href="{{route('redemptiontransactions.index')}}" class="svg-icon">
                    <i class="fas fa-money-check-alt"></i>
                    <span class="ml-4">{{__('nav.redemption_transactions')}}</span>
                </a>
            </li>
            @endcan

            @can('view-collection-transaction')
            <li class="{{ (strpos(Route::currentRouteName(), 'installercardpoints.index') === 0) ? 'active' : ''}}">
                <a href="{{route('installercardpoints.index')}}" class="svg-icon">
                    {{-- <i class="fas fa-user-secret"></i> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" id="Incognito-Mode--Streamline-Sharp-Remix" height="24" width="24"><desc>Incognito Mode Streamline Icon: https://streamlinehq.com</desc><g id="Remix/Programming/incognito-mode--internet-safe-mode-browser"><path id="Union" fill="#6c757d" fill-rule="evenodd" d="M5.18861 1H18.8114l0.1671 0.79399L20.7588 10.25H23v2.5H1v-2.5h2.24124l1.78021 -8.45601L5.18861 1ZM6.5 16.25c-1.24264 0 -2.25 1.0074 -2.25 2.25s1.00736 2.25 2.25 2.25 2.25 -1.0074 2.25 -2.25 -1.00736 -2.25 -2.25 -2.25Zm3.8409 -0.5452C9.47716 14.52 8.0785 13.75 6.5 13.75c-2.62335 0 -4.75 2.1266 -4.75 4.75s2.12665 4.75 4.75 4.75 4.75 -2.1266 4.75 -4.75c0 -0.4142 0.3358 -0.75 0.75 -0.75s0.75 0.3358 0.75 0.75c0 2.6234 2.1266 4.75 4.75 4.75s4.75 -2.1266 4.75 -4.75 -2.1266 -4.75 -4.75 -4.75c-1.5785 0 -2.9772 0.77 -3.8409 1.9548 -0.4857 -0.2889 -1.053 -0.4548 -1.6591 -0.4548 -0.6061 0 -1.1734 0.1659 -1.6591 0.4548ZM15.25 18.5c0 1.2426 1.0074 2.25 2.25 2.25s2.25 -1.0074 2.25 -2.25 -1.0074 -2.25 -2.25 -2.25 -2.25 1.0074 -2.25 2.25Z" clip-rule="evenodd" stroke-width="1"></path></g></svg>
                    <span class="ml-4">{{__('nav.installer_card_points_checking')}}</span>
                </a>
            </li>
            @endcan

            @can('create-collection-transaction')
            <li class="{{ (strpos(Route::currentRouteName(), 'returnproductdocuments.checking') === 0) ? 'active' : ''}}">
                <a href="#returnchecking" class="collapsed" data-toggle="collapse" aria-expanded="false">
                    {{-- <i class="fas fa-gifts"></i> --}}
                    <img src="{{ asset('images/return.png') }}" alt="" width="20" height="20">
                    <span class="ml-4">{{__('nav.return_process')}}</span>
                    <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="10 15 15 20 20 15"></polyline>
                        <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                    </svg>

                </a>
                <ul id="returnchecking" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                    <li class="{{ (strpos(Route::currentRouteName(), 'returnproductdocuments.checking') === 0) ? 'active' : ''}}">
                        <a href="{{route('returnproductdocuments.checking')}}">
                            <i class="las la-minus"></i><span> {{__('nav.return_product_document_checking')}}</span>
                        </a>
                    </li>
                    <li class="{{ (strpos(Route::currentRouteName(), 'returnchecks.index') === 0) ? 'active' : ''}}">
                        <a href="{{route('returnchecks.index')}}" class="">
                            <i class="las la-minus "></i><span class="text-wrap">{{__('nav.return_checks')}}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            {{-- <div class="dropdown-divider"></div> --}}

            {{-- <li class="nav-item text-center text-primary fw-bold">Lucky Draw Menu</li> --}}

            <li class="{{ (strpos(Route::currentRouteName(), 'faqs.index') === 0) ? 'active' : ''}}">
                <a href="{{route('faqs.index')}}" class="svg-icon">
                    <i class="fas fa-question-circle"></i>
                    {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" id="Incognito-Mode--Streamline-Sharp-Remix" height="24" width="24"><desc>Incognito Mode Streamline Icon: https://streamlinehq.com</desc><g id="Remix/Programming/incognito-mode--internet-safe-mode-browser"><path id="Union" fill="#6c757d" fill-rule="evenodd" d="M5.18861 1H18.8114l0.1671 0.79399L20.7588 10.25H23v2.5H1v-2.5h2.24124l1.78021 -8.45601L5.18861 1ZM6.5 16.25c-1.24264 0 -2.25 1.0074 -2.25 2.25s1.00736 2.25 2.25 2.25 2.25 -1.0074 2.25 -2.25 -1.00736 -2.25 -2.25 -2.25Zm3.8409 -0.5452C9.47716 14.52 8.0785 13.75 6.5 13.75c-2.62335 0 -4.75 2.1266 -4.75 4.75s2.12665 4.75 4.75 4.75 4.75 -2.1266 4.75 -4.75c0 -0.4142 0.3358 -0.75 0.75 -0.75s0.75 0.3358 0.75 0.75c0 2.6234 2.1266 4.75 4.75 4.75s4.75 -2.1266 4.75 -4.75 -2.1266 -4.75 -4.75 -4.75c-1.5785 0 -2.9772 0.77 -3.8409 1.9548 -0.4857 -0.2889 -1.053 -0.4548 -1.6591 -0.4548 -0.6061 0 -1.1734 0.1659 -1.6591 0.4548ZM15.25 18.5c0 1.2426 1.0074 2.25 2.25 2.25s2.25 -1.0074 2.25 -2.25 -1.0074 -2.25 -2.25 -2.25 -2.25 1.0074 -2.25 2.25Z" clip-rule="evenodd" stroke-width="1"></path></g></svg> --}}
                    <span class="ml-4">{{__('nav.faqs')}}</span>
                </a>
            </li>


            @can('view-user')
            <li class=" ">
                <a href="#member" class="collapsed" data-toggle="collapse" aria-expanded="false">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        class="svg-icon" id="p-dash2" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" width="20px" height="20px"
                        viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                        <path d="M55.517,46.55l-9.773-4.233c-0.23-0.115-0.485-0.396-0.704-0.771l6.525-0.005c0.114,0.011,2.804,0.257,4.961-0.67
                            c0.817-0.352,1.425-1.047,1.669-1.907c0.246-0.868,0.09-1.787-0.426-2.523c-1.865-2.654-6.218-9.589-6.354-16.623
                            c-0.003-0.121-0.397-12.083-12.21-12.18c-1.739,0.014-3.347,0.309-4.81,0.853c-0.319-0.813-0.789-1.661-1.488-2.459
                            C30.854,3.688,27.521,2.5,23,2.5s-7.854,1.188-9.908,3.53c-2.368,2.701-2.148,5.976-2.092,6.525v5.319c-0.64,0.729-1,1.662-1,2.625
                            v4c0,1.217,0.553,2.352,1.497,3.109c0.916,3.627,2.833,6.36,3.503,7.237v3.309c0,0.968-0.528,1.856-1.377,2.32l-8.921,4.866
                            C1.801,46.924,0,49.958,0,53.262V57.5h44h2h14v-3.697C60,50.711,58.282,47.933,55.517,46.55z M44,55.5H2v-2.238
                            c0-2.571,1.402-4.934,3.659-6.164l8.921-4.866C16.073,41.417,17,39.854,17,38.155v-4.019l-0.233-0.278
                            c-0.024-0.029-2.475-2.994-3.41-7.065l-0.091-0.396l-0.341-0.22C12.346,25.803,12,25.176,12,24.5v-4c0-0.561,0.238-1.084,0.67-1.475
                            L13,18.728V12.5l-0.009-0.131c-0.003-0.027-0.343-2.799,1.605-5.021C16.253,5.458,19.081,4.5,23,4.5
                            c3.905,0,6.727,0.951,8.386,2.828c0.825,0.932,1.24,1.973,1.447,2.867c0.016,0.07,0.031,0.139,0.045,0.208
                            c0.014,0.071,0.029,0.142,0.04,0.21c0.013,0.078,0.024,0.152,0.035,0.226c0.008,0.053,0.016,0.107,0.022,0.158
                            c0.015,0.124,0.027,0.244,0.035,0.355c0.001,0.009,0.001,0.017,0.001,0.026c0.007,0.108,0.012,0.21,0.015,0.303
                            c0,0.018,0,0.033,0.001,0.051c0.002,0.083,0.002,0.162,0.001,0.231c0,0.01,0,0.02,0,0.03c-0.004,0.235-0.02,0.375-0.02,0.378
                            L33,18.728l0.33,0.298C33.762,19.416,34,19.939,34,20.5v4c0,0.873-0.572,1.637-1.422,1.899l-0.498,0.153l-0.16,0.495
                            c-0.669,2.081-1.622,4.003-2.834,5.713c-0.297,0.421-0.586,0.794-0.837,1.079L28,34.123v4.125c0,0.253,0.025,0.501,0.064,0.745
                            c0.008,0.052,0.022,0.102,0.032,0.154c0.039,0.201,0.091,0.398,0.155,0.59c0.015,0.045,0.031,0.088,0.048,0.133
                            c0.078,0.209,0.169,0.411,0.275,0.605c0.012,0.022,0.023,0.045,0.035,0.067c0.145,0.256,0.312,0.499,0.504,0.723l0.228,0.281h0.039
                            c0.343,0.338,0.737,0.632,1.185,0.856l9.553,4.776C42.513,48.374,44,50.78,44,53.457V55.5z M58,55.5H46v-2.043
                            c0-3.439-1.911-6.53-4.986-8.068l-6.858-3.43c0.169-0.386,0.191-0.828,0.043-1.254c-0.245-0.705-0.885-1.16-1.63-1.16h-2.217
                            c-0.046-0.081-0.076-0.17-0.113-0.256c-0.05-0.115-0.109-0.228-0.142-0.349C30.036,38.718,30,38.486,30,38.248v-3.381
                            c0.229-0.28,0.47-0.599,0.719-0.951c1.239-1.75,2.232-3.698,2.954-5.799C35.084,27.47,36,26.075,36,24.5v-4
                            c0-0.963-0.36-1.896-1-2.625v-5.319c0.026-0.25,0.082-1.069-0.084-2.139c1.288-0.506,2.731-0.767,4.29-0.78
                            c9.841,0.081,10.2,9.811,10.21,10.221c0.147,7.583,4.746,14.927,6.717,17.732c0.169,0.24,0.22,0.542,0.139,0.827
                            c-0.046,0.164-0.178,0.462-0.535,0.615c-1.68,0.723-3.959,0.518-4.076,0.513h-6.883c-0.643,0-1.229,0.327-1.568,0.874
                            c-0.338,0.545-0.37,1.211-0.086,1.783c0.313,0.631,0.866,1.474,1.775,1.927l9.747,4.222C56.715,49.396,58,51.482,58,53.803V55.5z" />
                        <g>
                    </svg>
                    <span class="ml-4">{{__('nav.users')}}</span>
                    <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="10 15 15 20 20 15"></polyline>
                        <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                    </svg>
                </a>
                <ul id="member" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                    <li class="">
                        <a href="{{route('users.create')}}">
                            <i class="las la-minus"></i><span>{{__('nav.create_user')}} </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{route('users.index')}}">
                            <i class="las la-minus"></i><span>{{__('nav.user_list')}} </span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            @can('view-branch')
            <li class=" ">
                <a href="#branch"  class="collapsed" data-toggle="collapse" aria-expanded="false">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    class="svg-icon" id="p-dash2" fill="none" stroke="currentColor" stroke-width="10" stroke-linecap="round" width="20px" height="20px"
                    >
                        <defs>
                            <style>
                                .cls-1,
                                .cls-2 {
                                    fill: none;
                                    stroke: "currentColor";
                                    stroke-linecap: round;
                                    stroke-linejoin: round;
                                    stroke-width: 1.5px;
                                }

                                .cls-2 {
                                    fill-rule: evenodd;
                                }
                            </style>
                        </defs>
                        <g id="ic-places-castle">
                            <path class="cls-1" d="M2,7H22a0,0,0,0,1,0,0V20.8a.2.2,0,0,1-.2.2H2.2a.2.2,0,0,1-.2-.2V7A0,0,0,0,1,2,7Z" />
                            <path class="cls-2" d="M7.94,21V15a2,2,0,0,1,2-2H14a2,2,0,0,1,2,2v6" />
                            <path class="cls-2" d="M2,7V3.2A.2.2,0,0,1,2.2,3H5.8a.2.2,0,0,1,.2.2V7" />
                            <path class="cls-2" d="M10,7V3.2a.2.2,0,0,1,.2-.2h3.6a.2.2,0,0,1,.2.2V7" />
                            <path class="cls-2" d="M18,7V3.2a.2.2,0,0,1,.2-.2h3.6a.2.2,0,0,1,.2.2V7" />
                        </g>
                    </svg>
                    <span class="ml-4">{{__('nav.branch')}}</span>
                    <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="10 15 15 20 20 15"></polyline>
                        <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                    </svg>
                </a>
                <ul id="branch" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                    <input type="hidden" id="branch_open_status" value="1" />
                    <li class="">
                        <a href="http://192.168.3.242:7777" target="_blank">
                            <i class="las la-minus"></i><span class="mr-2">Lanthit</span><i id="lt_server_status" class="ri-wifi-line"></i>
                        </a>
                    </li>
                    <li class="">
                        <a href="http://192.168.21.242:7777" target="_blank">
                            <i class="las la-minus"></i><span class="mr-2">Theik Pan</span><i id="tp_server_status" class="ri-wifi-line"></i>
                        </a>
                    </li>
                    <li class="">
                        <a href="http://192.168.11.242:7777" target="_blank">
                            <i class="las la-minus"></i><span class="mr-2">Satsan</span><i id="ss_server_status" class="ri-wifi-line"></i>
                        </a>
                    </li>
                    <li class="">
                        <a href="http://192.168.16.242:7777" target="_blank">
                            <i class="las la-minus"></i><span class="mr-2">East Dagon</span><i id="ed_server_status" class="ri-wifi-line"></i>
                        </a>
                    </li>
                    <li class="">
                        <a href="http://192.168.31.242:7777" target="_blank">
                            <i class="las la-minus"></i><span class="mr-2">Mawlamyine</span><i id="mlm_server_status" class="ri-wifi-line"></i>
                        </a>
                    </li>
                    <li class="">
                        <a href="http://192.168.25.242:7777" target="_blank">
                            <i class="las la-minus"></i><span class="mr-2">Tampawady</span><i id="tpw_server_status" class="ri-wifi-line"></i>
                        </a>
                    </li>
                    <li class="">
                        <a href="http://192.168.36.242:7777" target="_blank">
                            <i class="las la-minus"></i><span class="mr-2">Hlaing Tharyar</span><i id="hty_server_status" class="ri-wifi-line"></i>
                        </a>
                    </li>
                    <li class="">
                        <a href="http://192.168.41.242:7777" target="_blank">
                            <i class="las la-minus"></i><span class="mr-2">Aye Tharyar</span><i id="aty_server_status" class="ri-wifi-line"></i>
                        </a>
                    </li>
                    <li class="">
                        <a href="http://192.168.46.242:7777" target="_blank">
                            <i class="las la-minus"></i><span class="mr-2">Terminal M</span><i id="tm_server_status" class="ri-wifi-line"></i>
                        </a>
                    </li>
                    <li class="">
                        <a href="http://192.168.51.243:7777" target="_blank">
                            <i class="las la-minus"></i><span class="mr-2">South Dagon</span><i id="sd_server_status" class="ri-wifi-line"></i>
                        </a>
                    </li>
                    <li class="">
                        <a href="http://192.168.56.242:7777" target="_blank">
                            <i class="las la-minus"></i><span class="mr-2">Shwe Pyi Thar</span><i id="spt_server_status" class="ri-wifi-line"></i>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            @can('view-role')
            <li class=" ">
                <a href="#role" class="collapsed" data-toggle="collapse" aria-expanded="false">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    class="svg-icon" id="p-dash2" fill="none" stroke="currentColor" stroke-width="10" stroke-linecap="round" width="20px" height="20px"
                    >
                        <defs>
                            <style>
                                .cls-1,
                                .cls-2 {
                                    fill: none;
                                    stroke: "currentColor";
                                    stroke-linecap: round;
                                    stroke-linejoin: round;
                                    stroke-width: 1.5px;
                                }

                                .cls-1 {
                                    fill-rule: evenodd;
                                }
                            </style>
                        </defs>
                        <g id="ic-security-secured-profile">
                            <path class="cls-1" d="M22,8.44c0-1.4-.16-2.64-.21-3.11a1.15,1.15,0,0,0-1.3-1c-.3,0-.95.12-1.68.15a7.35,7.35,0,0,1-2-.16,7.46,7.46,0,0,1-2.19-1.19A14.91,14.91,0,0,1,13,1.81a1.15,1.15,0,0,0-1.57,0A18.08,18.08,0,0,1,9.89,3.1a7.77,7.77,0,0,1-2.2,1.22,8,8,0,0,1-2.28.18,17.22,17.22,0,0,1-1.87-.18,1.14,1.14,0,0,0-1.3,1C2.19,5.8,2.06,7.05,2,8.44a16.94,16.94,0,0,0,.26,4.15,13,13,0,0,0,3.85,5.85,32.09,32.09,0,0,0,4.62,3.62,2.65,2.65,0,0,0,3,0,31.88,31.88,0,0,0,4.36-3.67,13.3,13.3,0,0,0,3.63-5.76A17.34,17.34,0,0,0,22,8.44Z" />
                            <path class="cls-1" d="M17,19.33V18a5,5,0,0,0-5-5h0a5,5,0,0,0-5,5v1.33" />
                            <circle class="cls-2" cx="12" cy="9.5" r="2.5" />
                        </g>
                    </svg>
                    <span class="ml-4">{{__('nav.roles')}}</span>
                    <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="10 15 15 20 20 15"></polyline>
                        <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                    </svg>
                </a>
                <ul id="role" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                    <li class="">
                        <a href="{{route('roles.create')}}">
                            <i class="las la-minus"></i><span>{{ __('nav.create_role')}}</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{route('roles.index')}}">
                            <i class="las la-minus"></i><span>{{ __('nav.role_list')}}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan


        </ul>
    </nav>
    <div class="p-3"></div>
</div>
<iframe id="test_a6_print" name="test_a6_print" src="{{ asset('test_print/test_pdf_A6.pdf') }}"></iframe>
<iframe id="test_a5_print" name="test_a5_print" src="{{ asset('test_print/test_pdf_A5.pdf') }}"></iframe>
