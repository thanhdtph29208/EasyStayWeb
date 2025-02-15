<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark"><!--begin::Sidebar Brand-->
    <div class="sidebar-brand"><!--begin::Brand Link-->
        <a href="<?= env('APP_URL') ?>/" class="brand-link"><!--begin::Brand Image-->
            <img src="/adminlte/assets/img/AdminLTELogo.png" alt="EasyStay Logo" class="brand-image opacity-75 shadow"><!--end::Brand Image--><!--begin::Brand Text-->
            <span class="brand-text fw-light">EasyStay</span><!--end::Brand Text-->
        </a><!--end::Brand Link-->
    </div><!--end::Sidebar Brand--><!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2"><!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item"><a href="{{ route('admin.dashboard.index') }}" class="nav-link"><i class="bi bi-speedometer2"></i>
                        <p>
                            Tổng Quan
                            <!-- <i class="nav-arrow bi bi-chevron-right"></i> -->
                        </p>
                    </a>
                    <!-- <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="../index.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                        <p>Dashboard v1</p>
                                    </a></li>
                                <li class="nav-item"><a href="../index2.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                        <p>Dashboard v2</p>
                                    </a></li>
                                <li class="nav-item"><a href="../index3.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                        <p>Dashboard v3</p>
                                    </a></li>
                            </ul> -->

                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-bookmark-dash"></i>
                        <p>
                            Quản Lý Loại Phòng
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('admin.loai_phong.index') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Danh sách</p>
                            </a></li>
                        <li class="nav-item"><a href="{{ route('admin.loai_phong.create') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Tạo mới</p>
                            </a></li>

                    </ul>
                </li>

                </li>

                <!-- <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>
                            Quản lý phòng
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('admin.phong.index') }}" class="nav-link"><i
                                    class="nav-icon bi bi-circle"></i>
                                <p>Danh sách</p>
                            </a></li>
                        <li class="nav-item"><a href="{{ route('admin.phong.create') }}" class="nav-link"><i
                                    class="nav-icon bi bi-circle"></i>
                                <p>Tạo mới</p>
                            </a></li>

                    </ul>
                </li> -->

                <li class="nav-item"><a href="{{ route('admin.user.index') }}" class="nav-link"><i class="bi bi-person"></i>
                        <p>
                            Quản Lý Người Dùng
                        </p>
                    </a>

                </li>

                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-ticket-perforated"></i>
                        <p>
                            Quản Lý Vai Trò
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('admin.vai_tro.index') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Danh sách</p>
                            </a></li>
                        <li class="nav-item"><a href="{{ route('admin.vai_tro.create') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Tạo mới</p>
                            </a></li>

                    </ul>
                </li>


                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-house-check"></i>
                        <p>
                            Quản Lý Đặt Phòng
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('admin.dat_phong.index') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Danh sách</p>
                            </a></li>
                        <li class="nav-item"><a href="{{ route('admin.dat_phong.create') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Tạo mới</p>
                            </a></li>

                    </ul>
                </li>

                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-ticket-perforated"></i>
                        <p>
                            Quản Lý Khuyến Mãi
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('admin.khuyen_mai.index') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Danh sách</p>
                            </a></li>
                        <li class="nav-item"><a href="{{ route('admin.khuyen_mai.create') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Tạo mới</p>
                            </a></li>

                    </ul>
                </li>

                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-postcard"></i>
                        <p>
                            Quản Lý Bài Viết
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('admin.bai_viet.index') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Danh sách</p>
                            </a></li>
                        <li class="nav-item"><a href="{{ route('admin.bai_viet.create') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Tạo mới</p>
                            </a></li>

                    </ul>
                </li>

                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-briefcase-fill"></i>
                        <p>
                            Quản Lý Dịch Vụ
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('admin.dich_vu.index') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Danh sách</p>
                            </a></li>
                        <li class="nav-item"><a href="{{ route('admin.dich_vu.create') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Tạo mới</p>
                            </a></li>

                    </ul>
                </li>

                <!-- <li class="nav-item"><a href="{{route('admin.danh_gia.index')}}" class="nav-link"><i
                            class="nav-icon bi bi-hand-thumbs-up-fill"></i>

                        <p>
                            Quản lý đánh giá
                        </p>
                    </a>
                </li> -->


                <li class="nav-item"><a href="{{ route('admin.khach_san.index') }}" class="nav-link"><i class="bi bi-info-circle"></i>
                        <p>
                            Quản Lý Khách Sạn
                        </p>
                    </a>

                </li>



            </li>

                <li class="nav-item"><a href="{{ route('admin.banners.index') }}" class="nav-link"><i class="bi bi-images"></i>
                        <p>
                            Quản lý Banner

                            <!-- <i class="nav-arrow bi bi-chevron-right"></i> -->

                        </p>
                    </a>
                </li>

                <li class="nav-item"><a href="{{ route('admin.lien_he.index') }}" class="nav-link"><i class="fa-regular fa-id-card"></i>
                        <p>
                            Quản lý liên hệ

                            <!-- <i class="nav-arrow bi bi-chevron-right"></i> -->

                        </p>
                    </a>
                </li>
                <!-- <li class="nav-item"><a href="{{ route('admin.dashboard.index') }}" class="nav-link"><i class="fa-regular fa-chart-bar"></i>
                        <p>
                            Thống kê

                        </p>
                    </a>
                </li> -->

                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-box-arrow-down"></i>
                        <p>
                            Tải xuống file Excel
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('admin.exportUser') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>User</p>
                            </a></li>
                        <li class="nav-item"><a href="{{ route('admin.exportHoaDon') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Hóa đơn</p>
                            </a></li>

                    </ul>
                </li>

            </ul><!--end::Sidebar Menu-->
        </nav>
    </div><!--end::Sidebar Wrapper-->
</aside><!--end::Sidebar--><!--begin::App Main-->
