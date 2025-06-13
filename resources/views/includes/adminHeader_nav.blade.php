<div id="kt_header" class="header">
    <div class="header-top align-items-stretch flex-grow-1">
        <div class="d-flex align-items-stretch container-fluid bg-dark">
            <div class="d-flex align-items-center align-items-lg-stretch me-5 flex-row-fluid">
                <button
                    class="d-lg-none btn btn-icon btn-color-white bg-hover-white bg-hover-opacity-10 w-35px h-35px h-md-40px w-md-40px ms-n3 me-2"
                    id="kt_header_navs_toggle">
                    <i class="ki-duotone ki-abstract-14 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </button>
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                    <span style="color:white;font-size:20px;font-weight:bold;">Admin Panel</span>
                    {{-- <img alt="Logo" src="{{ asset('/') }}assets/media/logos/logo.png" class="h-25px h-lg-30px" /> --}}
                </a>
                <div class="align-self-end" id="kt_brand_tabs">
                    <div class="mx-4 mb-5 header-tabs ms-lg-10 mb-lg-0" id="kt_header_tabs" data-kt-swapper="true"
                        data-kt-swapper-mode="prepend"
                        data-kt-swapper-parent="{default: '#kt_header_navs_wrapper', lg: '#kt_brand_tabs'}">
                        <ul class="nav flex-nowrap text-nowrap">
                            @php
                                $resultLmCat = App\Models\Acl\ModuleCategoryModel::orderBy(
                                    'display_order',
                                    'ASC',
                                )->get();
                            @endphp
                            @if ($resultLmCat)
                                @foreach ($resultLmCat as $rowLmCat)
                                    @php
                                        $resultLmModule = App\Models\Acl\RolePrivilgeModel::drawLeftMenu(
                                            1,
                                            $rowLmCat->ID,
                                        );
                                    @endphp
                                    @if (!$resultLmModule->isEmpty())
                                        @if ($rowLmCat->category_name == 'Dashboard')
                                            <li class="nav-item">
                                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                                    href="{{ url('dashboard') }}">Dashboard</a>
                                            </li>
                                        @else
                                            @if ($resultLmModule->count() == 1)
                                                @php
                                                    $singleModule = $resultLmModule->first();
                                                @endphp
                                                <li class="nav-item">
                                                    <a class="nav-link {{ request()->routeIs($singleModule->route) ? 'active' : '' }}"
                                                        href="{{ url($singleModule->route) }}">
                                                        {{ $rowLmCat->category_name }}
                                                    </a>
                                                </li>
                                            @else
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle {{ request()->routeIs($rowLmCat->route) ? 'active' : '' }}"
                                                        href="javascript:void(0)"
                                                        id="{{ $rowLmCat->category_name }}Dropdown"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        {{ $rowLmCat->category_name }}
                                                    </a>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="{{ $rowLmCat->category_name }}Dropdown">
                                                        @foreach ($resultLmModule as $module)
                                                            <li>
                                                                <a class="dropdown-item {{ Request::fullUrlIs(url($module->route)) ? 'active' : '' }}"
                                                                    href="{{ url($module->route) }}">
                                                                    {{ $module->module_name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center flex-row-auto">
                <div class="d-flex align-items-center ms-1">
                    <a href="#"
                        class="btn btn-icon btn-color-white bg-hover-white bg-hover-opacity-10 w-35px h-35px h-md-40px w-md-40px"
                        data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
                        data-kt-menu-placement="bottom-end">
                        <i class="ki-duotone ki-night-day theme-light-show fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                            <span class="path6"></span>
                            <span class="path7"></span>
                            <span class="path8"></span>
                            <span class="path9"></span>
                            <span class="path10"></span>
                        </i>
                        <i class="ki-duotone ki-moon theme-dark-show fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </a>
                    <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold fs-base w-150px"
                        data-kt-menu="true" data-kt-element="theme-mode-menu">
                        <div class="px-3 my-0 menu-item">
                            <a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="light">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-night-day fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                        <span class="path7"></span>
                                        <span class="path8"></span>
                                        <span class="path9"></span>
                                        <span class="path10"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Light</span>
                            </a>
                        </div>
                        <div class="px-3 my-0 menu-item">
                            <a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="dark">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-moon fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Dark</span>
                            </a>
                        </div>
                        <div class="px-3 my-0 menu-item">
                            <a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="system">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-screen fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">System</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center ms-1" id="kt_header_user_menu_toggle">
                    <div class="py-2 btn btn-flex align-items-center bg-hover-white bg-hover-opacity-10 ps-2 pe-2 me-n2"
                        data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                        data-kt-menu-placement="bottom-end">
                        <div class="d-none d-md-flex flex-column align-items-end justify-content-center me-2 me-md-4">
                            <span
                                class="text-white fs-8 fw-bold lh-1">{{ @Auth::guard('admin')->user()->employee->full_name }}</span>
                        </div>
                        <div class="symbol symbol-30px symbol-md-40px">
                            <img src="{{ photo(Auth::guard('admin')->user()->employee_ad_id) }}" alt="image" />
                        </div>
                    </div>
                    <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-275px"
                        data-kt-menu="true">
                        <div class="px-3 menu-item">
                            <div class="px-3 menu-content d-flex align-items-center">
                                <div class="symbol symbol-50px me-5">
                                    <img alt="Logo"
                                        src="{{ photo(Auth::guard('admin')->user()->employee_ad_id) }}" />
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">
                                        {{ @Auth::guard('admin')->user()->employee->full_name }}
                                    </div>
                                    <a href="#"
                                        class="fw-semibold text-muted text-hover-primary fs-7">{{ @Auth::guard('admin')->user()->employee->email_address }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="my-2 separator"></div>
                        <div class="px-5 menu-item">
                            <a href="{{ url('/profile') }}" class="px-5 menu-link">My Profile</a>
                        </div>
                        <div class="px-5 menu-item">
                            <a href="{{ url('/logout') }}" class="px-5 menu-link">Sign Out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
