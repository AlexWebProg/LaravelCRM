<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('manager.client.index',1) }}" class="brand-link">
        <img src="{{ asset('dist/img/VLogo.png') }}" alt="Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Панель управления</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/user.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('manager.client.index',1) }}"
                       class="nav-link {{ ( request()->route()->named('manager.client.*') && ( request()->route()->named('manager.client.create') || ( !empty(request()->route()->parameters['ob_status']) && request()->route()->parameters['ob_status'] === '1' ) || ( !empty($client->ob_status) && $client->ob_status === 1 ) ) ) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-star-o" aria-hidden="true"></i>
                        <p>
                            Объекты
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('manager.client_summary.index') }}"
                       class="nav-link {{ ( request()->route()->named('manager.client_summary.index') ) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-table" aria-hidden="true"></i>
                        <p>
                            Объекты - сводка
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('manager.map.index') }}"
                       class="nav-link {{ ( request()->route()->named('manager.map.*') ) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-map-marker" aria-hidden="true"></i>
                        <p>
                            Объекты на карте
                        </p>
                    </a>
                </li>
                @can('show_ob_status_2')
                <li class="nav-item">
                    <a href="{{ route('manager.client.index',2) }}"
                       class="nav-link {{ ( request()->route()->named('manager.client.*') && ( ( !empty(request()->route()->parameters['ob_status']) && request()->route()->parameters['ob_status'] === '2' ) || ( !empty($client->ob_status) && $client->ob_status === 2 ) ) ) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-wrench" aria-hidden="true"></i>
                        <p>
                            Объекты на гарантии
                        </p>
                    </a>
                </li>
                @endcan
                @can('show_ob_status_3')
                <li class="nav-item">
                    <a href="{{ route('manager.client.index',3) }}"
                       class="nav-link {{ ( request()->route()->named('manager.client.*') && ( ( !empty(request()->route()->parameters['ob_status']) && request()->route()->parameters['ob_status'] === '3' ) || ( !empty($client->ob_status) && $client->ob_status === 3 ) ) ) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-trash-o" aria-hidden="true"></i>
                        <p>
                            Объекты завершённые
                        </p>
                    </a>
                </li>
                @endcan
                @canany(['show_expenses_personal', 'show_all_expenses_personal', 'show_expenses_object', 'show_expenses_mat_report'])
                <li class="user-panel my-3"></li>
                @endcanany
                @can('show_expenses_personal')
                <li class="nav-item">
                    <a href="{{ route('manager.expenses_personal.index') }}"
                       class="nav-link {{ ( request()->route()->named('manager.expenses_personal.*') ) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-balance-scale" aria-hidden="true"></i>
                        <p>
                            Мой отчёт по расходам
                        </p>
                    </a>
                </li>
                @endcan
                @can('show_all_expenses_personal')
                    <li class="nav-item">
                        <a href="{{ route('manager.expenses_all_personal.managers_index') }}"
                           class="nav-link {{ ( request()->route()->named('manager.expenses_all_personal.*') ) ? 'active' : '' }}">
                            <i class="nav-icon fa fa-users" aria-hidden="true"></i>
                            <p>
                                Расходы сотрудников
                            </p>
                        </a>
                    </li>
                @endcan
                @can('show_expenses_object')
                    <li class="nav-item">
                        <a href="{{ route('manager.expenses_guarantee.index') }}"
                           class="nav-link {{ ( request()->route()->named('manager.expenses_guarantee.*') ) ? 'active' : '' }}">
                            <i class="nav-icon fa fa-wrench" aria-hidden="true"></i>
                            <p>
                                Расходы по гарантии
                            </p>
                        </a>
                    </li>
                @endcan
                @can('show_expenses_mat_report')
                    <li class="nav-item">
                        <a href="{{ route('manager.expenses_mat_report.index') }}"
                           class="nav-link {{ ( request()->route()->named('manager.expenses_mat_report.*') ) ? 'active' : '' }}">
                            <i class="nav-icon fa fa-calendar" aria-hidden="true"></i>
                            <p>
                                Отчёт по материалам
                            </p>
                        </a>
                    </li>
                @endcan
                @canany(['show_calls', 'show_quiz', 'create_survey', 'edit_quiz'])
                    <li class="user-panel my-3"></li>
                @endcanany
                @can('show_calls')
                    <li class="nav-item">
                        <a href="{{ route('manager.calls.month') }}"
                           class="nav-link {{ ( request()->route()->named('manager.calls.*') ) ? 'active' : '' }}">
                            <i class="nav-icon fa fa-phone-square" aria-hidden="true"></i>
                            <p>
                                Аналитика звонков
                            </p>
                        </a>
                    </li>
                @endcan
                @canany(['show_quiz', 'create_survey', 'edit_quiz'])
                    <li class="nav-item {{ (request()->route()->named('manager.quiz.*')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ (request()->route()->named('manager.quiz.*')) ? 'active' : '' }}">
                            <i class="nav-icon fa fa-pie-chart" aria-hidden="true"></i>
                            <p>
                                Опросы
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('create_survey')
                            <li class="nav-item">
                                <a href="{{ route('manager.quiz.survey.create') }}" class="nav-link {{ (request()->route()->named('manager.quiz.survey.create')) ? 'active' : '' }}">
                                    <i class="fa fa-play nav-icon" aria-hidden="true"></i>
                                    <p>Провести</p>
                                </a>
                            </li>
                            @endcan
                            @can('show_quiz')
                            <li class="nav-item">
                                <a href="{{ route('manager.quiz.template.results_index') }}" class="nav-link {{ (request()->route()->named('manager.quiz.template.results_index') || request()->route()->named('manager.quiz.template.show')) ? 'active' : '' }}">
                                    <i class="fa fa-pie-chart nav-icon" aria-hidden="true"></i>
                                    <p>Результаты</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('manager.quiz.survey.index') }}" class="nav-link {{ (request()->route()->named('manager.quiz.survey.index') || request()->route()->named('manager.quiz.survey.show')) ? 'active' : '' }}">
                                    <i class="fa fa-list-ul nav-icon" aria-hidden="true"></i>
                                    <p>Проведённые опросы</p>
                                </a>
                            </li>
                            @endcan
                            @can('edit_quiz')
                                <li class="nav-item">
                                    <a href="{{ route('manager.quiz.template.index') }}" class="nav-link {{ (request()->route()->named('manager.quiz.template.index') || request()->route()->named('manager.quiz.template.create') || request()->route()->named('manager.quiz.template.edit')) ? 'active' : '' }}">
                                        <i class="fa fa-file-text-o nav-icon" aria-hidden="true"></i>
                                        <p>Шаблоны</p>
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">&nbsp;</li>
                        </ul>
                    </li>
                @endcanany
                <li class="user-panel my-3"></li>
                @if (!empty(auth()->user()->is_admin))
                    <li class="nav-item">
                        <a href="{{ route('manager.manager.index',1) }}"
                           class="nav-link {{ ( request()->route()->named('manager.manager.*') && ( !empty(request()->route()->parameters['is_admin']) || ( isset($manager->is_admin) && !empty($manager->is_admin) ) ) ) ? 'active' : '' }}">
                            <i class="nav-icon fa fa-user-secret" aria-hidden="true"></i>
                            <p>
                                Руководство
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manager.manager.index',0) }}"
                           class="nav-link {{ ( request()->route()->named('manager.manager.*') && empty(request()->route()->parameters['is_admin']) && empty($manager->is_admin) ) ? 'active' : '' }}">
                            <i class="nav-icon fa fa-user-o" aria-hidden="true"></i>
                            <p>
                                Сотрудники
                            </p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('manager.contact.index') }}"
                       class="nav-link {{ ( request()->route()->named('manager.contact.*') ) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-phone" aria-hidden="true"></i>
                        <p>
                            Контакты сотрудников
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('manager.partner.index') }}"
                       class="nav-link {{ ( request()->route()->named('manager.partner.*') ) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-bookmark-o" aria-hidden="true"></i>
                        <p>
                            Партнёры
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('manager.faq.index') }}"
                       class="nav-link {{ ( request()->route()->named('manager.faq.*') ) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-info-circle" aria-hidden="true"></i>
                        <p>
                            Частые вопросы
                        </p>
                    </a>
                </li>
                @if (!empty(auth()->user()->is_admin))
                    <li class="user-panel my-3"></li>
                    <li class="nav-item">
                        <a href="{{ route('manager.gen_mes.create') }}"
                           class="nav-link {{ ( request()->route()->named('manager.gen_mes.*') ) ? 'active' : '' }}">
                            <i class="nav-icon fa fa-comments-o" aria-hidden="true"></i>
                            <p>
                                Общая рассылка
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manager.settings.edit') }}"
                           class="nav-link {{ (request()->route()->named('manager.settings.edit')) ? 'active' : '' }}">
                            <i class="nav-icon fa fa-cog" aria-hidden="true"></i>
                            <p>
                                Настройки системы
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
