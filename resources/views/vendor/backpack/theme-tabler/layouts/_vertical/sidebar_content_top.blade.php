@if ($auth ?? true)
    @if (backpack_auth()->guest())
        <x-backpack::menu-item :title="trans('backpack::base.login')" :link="route('backpack.auth.login')"
                               icon="la la-sign-in-alt"/>
    @else
        <li class="nav-item dropdown d-none d-lg-block">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="true">
                <span class="avatar avatar-sm rounded-circle me-2">
                    <img class="avatar avatar-sm rounded-circle bg-transparent"
                         src="{{ backpack_avatar_url(backpack_auth()->user()) }}"
                         alt="{{ backpack_auth()->user()->name }}" onerror="this.style.display='none'"
                         style="margin: 0;position: absolute;left: 0;z-index: 1;">
                    <span class="avatar avatar-sm rounded-circle backpack-avatar-menu-container text-center">
                        {{ backpack_user()->getAttribute('name') ? mb_substr(backpack_user()->name, 0, 1, 'UTF-8') : 'A' }}
                    </span>
                </span>
                {{ backpack_user()->name }}
            </a>
            <div class="dropdown-menu" data-bs-popper="static">
                @if (config('backpack.base.setup_my_account_routes'))
                    <x-backpack::menu-dropdown-item :title="trans('backpack::base.my_account')"
                                                    :link="route('backpack.account.info')" icon="la la-lock"/>
                @endif
                <x-backpack::menu-dropdown-item :title="trans('backpack::base.logout')" :link="backpack_url('logout')"
                                                icon="la la-sign-out-alt text-danger"/>
            </div>
            @include('backpack.language-switcher::language-switcher', [
                'main_label' => true,
            ])
            @include('buttons.switch-layout')
        </li>
    @endif
    <x-backpack::menu-separator/>
@endif
