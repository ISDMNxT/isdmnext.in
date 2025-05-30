<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>
        <?= $this->ki_theme->get_title() ?> - Employer Panel
    </title>
    <meta charset="utf-8" />
    <meta name="description" content="
            The most advanced Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, 
            Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel versions. 
            Grab your copy now and get life-time updates for free.
        " />
    <meta name="keywords" content="
            metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, 
            Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, 
            free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, 
            bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon
        " />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Employer Panel" />
    <meta property="og:url" content="https://isdmnext.in" />
    <meta property="og:site_name" content="Independent Skill Development Mission" />
    <link rel="canonical" href="<?= base_url() ?>/metronic8" />
    <link rel="shortcut icon" href="{base_url}assets/media/logos/favicon.ico" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{base_url}assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{base_url}assets/plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet"
        type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{base_url}assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{base_url}assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css" />
    <!--end::Global Stylesheets Bundle-->
    <link rel="stylesheet" href="{base_url}assets/icon-picker/dist/iconpicker-1.5.0.css">
    <script src="{base_url}assets/icon-picker/dist/iconpicker-1.5.0.js"></script>
    <link rel="stylesheet" href="{base_url}assets/custom/custom.css">
    <style>
        tr .eye-btn {
            display: none;
        }

        .click-to-copy {
            user-select: none;
            cursor: pointer;
        }

        tr:hover .eye-btn {
            display: inline-block;
        }

        .card-image {
            background-position: 100% 50%;
            background-repeat: no-repeat;
            background-image:url('{base_url}assets/media/stock/900x600/42.png')
        }

        .table-container {
            overflow: scroll;
        }

        .table-container table th,
        .table-container table td {
            white-space: nowrap;
            padding: 10px 20px;
            font-family: Arial;
        }

        .table-container table tr th:first-child,
        .table-container table td:first-child {
            position: sticky;
            width: 100px;
            left: 0;
            z-index: 10;
        }

        .table-container table tr th:first-child {
            z-index: 11;
        }

        .table-container table tr th {
            position: sticky;
            top: 0;
            z-index: 9;
        }

        .hide {
            display: none;
        }

        .custom_setting_input {
            font-size: 21px;
            background: transparent;
            border: 0;
            outline: 0;
        }

        .br-none {
            border-radius: 0px !important
        }

        [data-bs-theme=dark] #datatables_buttons_info {
            background-color: var(--bs-scrollbar-color)
        }

        #IconPreview {
            color: black !important;
        }

        [data-bs-theme=dark] #IconPreview {
            color: white !important;
        }

        #file-list {
            display: flex;
            flex-wrap: wrap;
        }
    </style>
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_app_body" class="page-loading" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default"
    <?= sidebar_toggle('data-kt-app-sidebar-minimize="on"') ?>>
    <?php
    if (CHECK_PERMISSION('WALLET_SYSTEM_COURSE_WISE')) {
        echo '<input type="hidden" id="wallet_system_course_wise">';
    }
    ?>
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }            
    </script>
    <!--end::Theme mode setup on page load-->

    <!--begin::Page loading(append to body)-->
    <div class="page-loader flex-column bg-dark bg-opacity-25">
        <span class="spinner-border text-primary" role="status"></span>
        <span class="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>
    </div>
    <!--end::Page loading-->
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
            <!--begin::Header-->
            <div id="kt_app_header" class="app-header " data-kt-sticky="true"
                data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize"
                data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
                <!--begin::Header container-->
                <div class="app-container  container-fluid d-flex align-items-stretch justify-content-between "
                    id="kt_app_header_container">
                    <!--begin::Sidebar mobile toggle-->
                    <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
                        <div class="btn btn-icon btn-active-color-primary w-35px h-35px"
                            id="kt_app_sidebar_mobile_toggle">
                            <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </div>
                    </div>
                    <!--end::Sidebar mobile toggle-->
                    <!--begin::Mobile logo-->
                    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                        <a href="{base_url}admin" class="text-capitalize text-warning fs-1 fw-bold">
                            <!-- <img alt="Logo" src="{base_url}assets/media/logos/default-small.svg" class="h-30px" /> -->
                            <?php
                            // echo ucfirst($this->session->userdata('admin_type'));
                            if ($this->center_model->isEmployer()) {
                                echo ucfirst($center_data['institute_name']);
                            }
                            ?>
                        </a>
                    </div>
                    <!--end::Mobile logo-->
                    <!--begin::Header wrapper-->
                    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1"
                        id="kt_app_header_wrapper">
                        <!--begin::Menu wrapper-->
                        <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
                            data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end"
                            data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
                            data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
                            data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                        </div>
                        <!--end::Menu wrapper-->
                        <!--begin::Navbar-->
                        <div class="app-navbar flex-shrink-0">

                            <?php
                            if ($this->center_model->isEmployer() && (CHECK_PERMISSION('WALLET_SYSTEM') or CHECK_PERMISSION('WALLET_SYSTEM_COURSE_WISE'))) {
                                ?>
                                <!--begin::Wallet-->
                                <div class="app-navbar-item ms-1 ms-md-4 text-center ">
                                    <!--begin::Menu wrapper-->
                                    <label class="border border-primary border-dotted p-2 fs-2"
                                        style="border-radius:10px;min-width:200px">
                                        <span data-kt-countup="true" data-kt-countup-value="<?= $center_data['wallet'] ?>"
                                            data-kt-countup-prefix='{inr}'>0</span>

                                        <a class="btn btn-primary p-1 btn-sm" href="https://rzp.io/l/isdm-wallet" target="_blank">&nbsp;<i class="fa fa-plus"></i></a>
                                        <a href="{base_url}admin/wallet-history?id=<?= $center_data['id'] ?>" class="btn btn-warning p-1 btn-sm"
                                            title="History">&nbsp;<i class="fa fa-history"></i></a>

                                        <small class="d-flex" style="font-size:12px;    justify-content: center;">My
                                            Wallet</small>
                                    </label>
                                    <!--end::Menu wrapper-->
                                </div>
                                <!--end::Wallet-->

                                <?php
                            } ?>

                            <!--begin::Chat-->
                            <div class="app-navbar-item ms-1 ms-md-4">
                                <!--begin::Menu wrapper-->
                                <a href="{base_url}" target="_blank"
                                    class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative"
                                    id="kt_drawer_chat_toggle">
                                    <i class="ki-outline ki-exit-right-corner fs-2"> </i>
                                    <span
                                        class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink">
                                    </span>
                                </a>
                                <!--end::Menu wrapper-->
                            </div>
                            <!--end::Chat-->
                            <!--begin::Theme mode-->
                            <div class="app-navbar-item ms-1 ms-md-4">
                                <!--begin::Menu toggle-->
                                <a href="#"
                                    class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px"
                                    data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
                                    data-kt-menu-placement="bottom-end">
                                    <i class="ki-duotone ki-night-day theme-light-show fs-1"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span><span
                                            class="path5"></span><span class="path6"></span><span
                                            class="path7"></span><span class="path8"></span><span
                                            class="path9"></span><span class="path10"></span></i> <i
                                        class="ki-duotone ki-moon theme-dark-show fs-1"><span class="path1"></span><span
                                            class="path2"></span></i></a>
                                <!--begin::Menu toggle-->
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                                    data-kt-menu="true" data-kt-element="theme-mode-menu">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3 my-0">
                                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                            data-kt-value="light">
                                            <span class="menu-icon" data-kt-element="icon">
                                                <i class="ki-duotone ki-night-day fs-2"><span class="path1"></span><span
                                                        class="path2"></span><span class="path3"></span><span
                                                        class="path4"></span><span class="path5"></span><span
                                                        class="path6"></span><span class="path7"></span><span
                                                        class="path8"></span><span class="path9"></span><span
                                                        class="path10"></span></i> </span>
                                            <span class="menu-title">
                                                Light
                                            </span>
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3 my-0">
                                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                            data-kt-value="dark">
                                            <span class="menu-icon" data-kt-element="icon">
                                                <i class="ki-duotone ki-moon fs-2"><span class="path1"></span><span
                                                        class="path2"></span></i> </span>
                                            <span class="menu-title">
                                                Dark
                                            </span>
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3 my-0">
                                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                            data-kt-value="system">
                                            <span class="menu-icon" data-kt-element="icon">
                                                <i class="ki-duotone ki-screen fs-2"><span class="path1"></span><span
                                                        class="path2"></span><span class="path3"></span><span
                                                        class="path4"></span></i> </span>
                                            <span class="menu-title">
                                                System
                                            </span>
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </div>
                            <!--end::Theme mode-->
                            <!--begin::User menu-->
                            <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
                                <!--begin::Menu wrapper-->
                                <div class="cursor-pointer symbol symbol-35px"
                                    data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                                    data-kt-menu-placement="bottom-end">
                                    <img src="{profile_image}" class="rounded-3 owner-image" alt="user" />
                                </div>
                                <!--begin::User account menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                                    data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content d-flex align-items-center px-3">
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-50px me-5">
                                                <img alt="Logo" src="{profile_image}" class="owner-image" />
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Username-->
                                            <div class="d-flex flex-column">
                                                <div class="fw-bold d-flex align-items-center fs-5">
                                                    {owner_name} <span
                                                        class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">{type}</span>
                                                </div>
                                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                                    {owner_email} </a>
                                            </div>
                                            <!--end::Username-->
                                        </div>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu separator-->
                                    <div class="separator my-2"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Menu item-->
                                    <?php if(!empty($this->session->userdata('admin_id'))){ ?>
                                    <div class="menu-item px-5">
                                        <a href="{base_url}employer/employer_profile" class="menu-link px-5">
                                            My Profile
                                        </a>
                                    </div>
                                    <?php } ?>
                                    <!--end::Menu item-->
                                    
                                    <div class="separator my-2"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Menu item-->
                                    <?php if(!empty($this->session->userdata('admin_id'))){ ?>
                                    <div class="menu-item px-5 set-sound-setting"
                                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                        data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                                        <span class="menu-link px-5 ">
                                            <span class="menu-title position-relative">
                                                Sound
                                                <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                                    <i
                                                        class="ki-duotone ki-cross-square sound-no fs-2 bg-danger text-black">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <i
                                                        class="ki-duotone ki-check-square bg-success text-black fs-2 sound-yes"><span
                                                            class="path1"></span><span class="path2"></span></i>
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                    <!--end::Menu item-->
                                    <?php } ?>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-5">
                                        <a href="{base_url}admin/sign-out" class="menu-link px-5">
                                            Sign Out
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::User account menu-->
                                <!--end::Menu wrapper-->
                            </div>
                            <!--end::User menu-->
                            <!--begin::Header menu toggle-->
                            <div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
                                <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px"
                                    id="kt_app_header_menu_toggle">
                                    <i class="ki-duotone ki-element-4 fs-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                                </div>
                            </div>
                            <!--end::Header menu toggle-->
                            <!--begin::Aside toggle-->
                            <!--end::Header menu toggle-->
                        </div>
                        <!--end::Navbar-->
                    </div>
                    <!--end::Header wrapper-->
                </div>
                <!--end::Header container-->
            </div>
            <!--end::Header-->
            <!--begin::Wrapper-->
            <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
                <!--begin::Sidebar-->
                <div id="kt_app_sidebar" class="app-sidebar  flex-column " data-kt-drawer="true"
                    data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
                    data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start"
                    data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
                    <!--begin::Logo-->
                    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
                        <!--begin::Logo image-->
                        <a href="{base_url}admin" class="fs-1">
                            <?php
                            $aaTypeN = $this->session->userdata('admin_type');
                            echo ucfirst($aaTypeN) . ' Panel';
                            ?>
                        </a>
                        <div id="kt_app_sidebar_toggle"
                            class="app-sidebar-toggle <?= sidebar_toggle('active') ?> btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate "
                            data-kt-toggle="<?= sidebar_toggle('active', 'true') ?>" data-kt-toggle-state="active"
                            data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
                            <i class="ki-duotone ki-black-left-line fs-3 rotate-180"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </div>
                        <!--end::Sidebar toggle-->
                    </div>
                    <!--end::Logo-->
                    <!--begin::sidebar menu-->
                    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
                        <!--begin::Menu wrapper-->
                        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
                            <!--begin::Scroll wrapper-->
                            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
                                data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                                data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
                                data-kt-scroll-save-state="true">
                                <!--begin::Menu-->
                                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6"
                                    id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                                    {menu_item}
                                </div>
                                <!--end::Menu-->
                            </div>
                            <!--end::Scroll wrapper-->
                        </div>
                        <!--end::Menu wrapper-->
                    </div>
                    <!--end::sidebar menu-->
                    <!--begin::Footer-->
                    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">

                        <?php
                        if ($this->center_model->isAdmin() or $this->session->userdata('temp_login')) {

                            ?>
                            <div class="d-flex flex-column flex-center flex-column-fluid">
                                <?php
                                if ($this->session->userdata('temp_login')) {
                                    echo '<a href="' . base_url('admin/switch-back') . '" class="btn btn-sm btn-light btn-active-light-primary me-2"><i class="fa fa-arrow-left"></i> Switch Back</a>';
                                } else {
                                    ?>
                                    <form action="" method="POST">
                                        <input type="hidden" name="status" value="temp_login">
                                        <lable class="form-label">Login As Centre</lable>
                                        <div class="input-group d-flex">
                                            <div class="input-group-text p-0" style="width:174px">

                                                <select data-control="select2" required data-placeholder="Select Centre"
                                                    name="center_id" class="form-control" autocomplete="off">
                                                    <option></option>
                                                    <?php
                                                    $centers = $this->center_model->get_center();
                                                    if ($centers->num_rows()) {
                                                        foreach ($centers->result() as $rowc) {
                                                            echo '<option value="' . $rowc->id . '">' . $rowc->institute_name . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <button class="input-group-text btn-success btn btn-xs btn-sm" id="basic-addon2">
                                                <i class="fa fa-sign-in"></i>
                                            </button>
                                        </div>

                                    </form>

                                    <?php
                                }
                                echo '  </div>';
                        } 
                        ?>

                        </div>
                        <!--end::Footer-->
                    </div>
                    <!--end::Sidebar-->
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">
                            <?php
                            echo $this->ki_theme->get_breadcrumb();
                            ?>
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container  container-xxl ">
                                    <!-- {wallet_message} -->
                                    {page_output}
                                </div>
                                <!--end::Content container-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Content wrapper-->
                        <!--begin::Footer-->
                        <div id="kt_app_footer" class="app-footer ">
                            <!--begin::Footer container-->
                            <div
                                class="app-container  container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3 ">
                                <!--begin::Copyright-->
                                <div class="text-dark order-2 order-md-1">
                                    <!-- <span class="text-muted fw-semibold me-1">2024&copy;</span>
                                    <a href="" target="_blank" class="text-gray-800 text-hover-primary">Arya</a> -->
                                </div>
                                <!--end::Copyright-->
                            </div>
                            <!--end::Footer container-->
                        </div>
                        <!--end::Footer-->
                    </div>
                    <!--end:::Main-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
        </div>
        <!--end::App-->
        <!--begin::Scrolltop-->
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="ki-duotone ki-arrow-up"><span class="path1"></span><span class="path2"></span></i>
        </div>
        <!--end::Scrolltop-->
        <!--begin::View component-->
        <div id="kt_drawer_view_details_box" data-kt-drawer="true" data-kt-drawer-activate="true"
            data-kt-drawer-toggle="#kt_drawer_example_advanced_button"
            data-kt-drawer-close="#kt_drawer_example_advanced_close" data-kt-drawer-name="docs"
            data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}"
            data-kt-drawer-direction="start">
            <div class="card rounded-0 w-100">
                <!--begin::Card header-->
                <div class="card-header pe-5">
                    <!--begin::Title-->
                    <div class="card-title">
                        <!--begin::User-->
                        <div class="d-flex justify-content-center flex-column me-3">
                            <strong class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 lh-1 title">Example
                                Advanced</strong>
                        </div>
                        <!--end::User-->
                    </div>
                    <!--end::Title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Close-->
                        <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_example_advanced_close">
                            <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body hover-scroll-overlay-y">
                </div>
                <!--end::Card body-->
            </div>
        </div>
        <div class="modal fade" tabindex="-1" id="mymodal">
            <form class="modal-dialog  modal-dialog-centered  modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title title">Modal title</h3>
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <div class="modal-body body">
                        <p>Modal body text goes here.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-outline hover-rotate-end btn-outline-dashed btn-outline-danger"
                            data-bs-dismiss="modal">Close</button>
                        {update_button}
                    </div>
                </div>
            </form>
        </div>
        <!--begin::Drawer-->
        <div id="kt_drawer_example_advanced" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true"
            data-kt-drawer-permanent="true" data-kt-drawer-toggle="#kt_drawer_example_advanced_button"
            data-kt-drawer-close="#kt_drawer_example_advanced_close" data-kt-drawer-name="set_in_page"
            data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}"
            data-kt-drawer-direction="end" <?= $this->ki_theme->drawer_main_div_attr() ?>>
            <!--begin::Card-->
            <div class="card rounded-0 w-100">
                <!--begin::Card header-->
                <div class="card-header pe-5">
                    <!--begin::Title-->
                    <div class="card-title">
                        <!--begin::User-->
                        <div class="d-flex justify-content-center flex-column me-3">
                            <a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 lh-1">Example
                                Advanced</a>
                        </div>
                        <!--end::User-->
                    </div>
                    <!--end::Title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Close-->
                        <div class="btn btn-sm btn-icon btn-light-danger" id="kt_drawer_example_advanced_close">
                            <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body hover-scroll-overlay-y">
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Drawer-->
        <!--end::View component-->
</body>
<!--end::Body-->
<!--begin::Javascript-->
<script>
    var base_url = "<?= base_url() ?>",
        ajax_url = base_url + 'ajax/';
    const login_type = '<?= $this->center_model->login_type() ?>';
    const is_staff_login = 'N';
    const all_templates = '';
    const wallet_system = true;
    const wallet_balance = <?= $this->ki_theme->wallet_balance() ?? 0 ?>;
    // console.log(content_css);
    // Default vars of this project
    <?php
    foreach ($this->ki_theme->default_vars() as $var => $var_value) {
        ?>const <?= $var ?> = `<?= $var_value ?>`;
        <?php
    }
    if ($this->center_model->isCenter() && CHECK_PERMISSION('WALLET_SYSTEM')) {
        $get = $this->ki_theme->center_fix_fees();
        foreach ($get as $key => $amount) {
            echo 'const ' . strtoupper($key) . ' = ' . $amount . ";\n\t";
        }
    }
    ?>   
</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{base_url}assets/plugins/global/plugins.bundle.js"></script>
<script src="{base_url}assets/js/scripts.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script src="https://mark-freq32.c9.io/mark3/js/jquery.ui.touch-punch.min.js"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<script src="{base_url}assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="{base_url}assets/plugins/custom/vis-timeline/vis-timeline.bundle.js"></script>
<script src="{base_url}assets/lib/5/index.js"></script>
<script src="{base_url}assets/lib/5/xy.js"></script>
<script src="{base_url}assets/lib/5/percent.js"></script>
<script src="{base_url}assets/lib/5/radar.js"></script>
<script src="{base_url}assets/lib/5/themes/Animated.js"></script>
<!--end::Vendors Javascript-->
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
<!--begin::Custom Javascript(used for this page only)-->
<script src="{base_url}assets/js/widgets.bundle.js"></script>
<script src="{base_url}assets/js/custom/widgets.js"></script>
<script src="{base_url}assets/js/custom/apps/chat/chat.js"></script>
<script src="{base_url}assets/js/custom/utilities/modals/upgrade-plan.js"></script>
<script src="{base_url}assets/js/custom/utilities/modals/create-app.js"></script>
<script src="{base_url}assets/js/custom/utilities/modals/create-campaign.js"></script>
<script src="{base_url}assets/js/custom/utilities/modals/create-project/type.js"></script>
<script src="{base_url}assets/js/custom/utilities/modals/create-project/budget.js"></script>
<script src="{base_url}assets/js/custom/utilities/modals/create-project/settings.js"></script>
<script src="{base_url}assets/js/custom/utilities/modals/create-project/team.js"></script>
<script src="{base_url}assets/js/custom/utilities/modals/create-project/targets.js"></script>
<script src="{base_url}assets/js/custom/utilities/modals/create-project/files.js"></script>
<script src="{base_url}assets/js/custom/utilities/modals/create-project/complete.js"></script>
<script src="{base_url}assets/js/custom/utilities/modals/create-project/main.js"></script>
<script src="{base_url}assets/js/custom/utilities/modals/users-search.js"></script>
<script src="{base_url}assets/custom/jquery.nestable.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"
    integrity="sha512-6JR4bbn8rCKvrkdoTJd/VFyXAN4CE9XMtgykPWgKiHjou56YDJxWsi90hAeMTYxNwUnKSQu9JPc3SQUg+aGCHw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<style>
    .tox-promotion {
        display: none !important;
    }
</style>
<script src="{base_url}assets/custom/custom.js"></script>
<!--end::Custom Javascript-->
<!--end::Javascript-->
{js_file}

</html>