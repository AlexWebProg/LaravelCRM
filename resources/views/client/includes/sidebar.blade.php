<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('client.main') }}" class="brand-link">
        <img src="{{ asset('dist/img/VLogo.png') }}" alt="Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Личный кабинет</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex user_panel_with_address">
            <div class="image">
                <img src="{{ asset('dist/img/user.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                {{ auth()->user()->name }}
                <small class="d-block mt-1">{{ auth()->user()->address }}</small>
                @if (auth()->user()->ob_status === 2)
                <small class="d-block mt-2">Объект на гарантийном обслуживании</small>
                @endif
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('client.main') }}"
                       class="nav-link {{ (request()->route()->named('client.main')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-home" aria-hidden="true"></i>
                        <p>
                            Рабочий стол
                        </p>
                    </a>
                </li>
                @if (!empty(auth()->user()->webcam))
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link webcam_link">
                        <i class="nav-icon fa fa-video-camera" aria-hidden="true"></i>
                        <p>
                            Веб-камера
                        </p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('client.photos') }}"
                       class="nav-link {{ (request()->route()->named('client.photos')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-camera" aria-hidden="true"></i>
                        <p>
                            Фото
                            <span class="right badge badge-danger d-none newPhotoBadge">0</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('client.plan') }}"
                       class="nav-link {{ (request()->route()->named('client.plan')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-info-circle" aria-hidden="true"></i>
                        <p>
                            План работ
                            <span class="right badge badge-danger d-none newPlanBadge">0</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('client.estimate') }}"
                       class="nav-link {{ (request()->route()->named('client.estimate')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-file-text-o" aria-hidden="true"></i>
                        <p>
                            Смета
                        </p>
                        <span class="right badge badge-danger d-none newEstimateBadge">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('client.chat') }}"
                       class="nav-link {{ (request()->route()->named('client.chat')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-comments-o" aria-hidden="true"></i>
                        <p>
                            Чат
                            <span class="right badge badge-danger d-none newChatMessageBadge">0</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('client.tech_doc') }}"
                       class="nav-link {{ (request()->route()->named('client.tech_doc*')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-file-pdf-o" aria-hidden="true"></i>
                        <p>
                            Технич документация
                            <span class="right badge badge-danger d-none newTechDocBadge">0</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="https://www.dreamcompany.ru/priem-platezhej" data-statclick="pay" target="_blank" class="nav-link">
                        <i class="nav-icon fa fa-credit-card" aria-hidden="true"></i>
                        <p>
                            Оплата
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('client.contact') }}"
                       class="nav-link {{ (request()->route()->named('client.contact')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-phone" aria-hidden="true"></i>
                        <p>
                            Контакты сотрудников
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('client.partner') }}"
                       class="nav-link {{ (request()->route()->named('client.partner')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-bookmark-o" aria-hidden="true"></i>
                        <p>
                            Партнёры
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('client.faq') }}"
                       class="nav-link {{ (request()->route()->named('client.faq')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-info-circle" aria-hidden="true"></i>
                        <p>
                            Частые вопросы
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
