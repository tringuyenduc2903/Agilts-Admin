<x-backpack::menu-item :title="trans('backpack::base.dashboard')" :link="backpack_url('dashboard')"
                       icon="la la-home nav-icon"/>
@php
    use App\Enums\Permission;

    $menu_items = [
        [
            'title' => trans('Customers'),
            'icon' => 'la la-user',
            'columns' => [
                [
                    'items' => [
                        [
                            'permission' => Permission::CUSTOMER_CRUD,
                            'title' => trans('All customers'),
                            'link' => backpack_url('customer'),
                        ],
                    ],
                ],
            ],
        ],
        [
            'title' => trans('System'),
            'icon' => 'la la-gear',
            'columns' => [
                [
                    'title' => trans('Permissions'),
                    'items' => [
                        [
                            'permission' => Permission::ADMIN_CRUD,
                            'title' => trans('backpack::permissionmanager.users'),
                            'link' => backpack_url('admin'),
                        ],
                        [
                            'permission' => Permission::ROLE_CRUD,
                            'title' => trans('backpack::permissionmanager.roles'),
                            'link' => backpack_url('role'),
                        ],
                        [
                            'permission' => Permission::PERMISSION_CRUD,
                            'title' => trans('backpack::permissionmanager.permission_plural'),
                            'link' => backpack_url('permission'),
                        ],
                    ],
                ],
            ],
        ],
    ];
@endphp
@foreach ($menu_items as $item)
    @php
        $permissions = [];

        foreach ($item['columns'] as $column) {
            foreach ($column['items'] as $item_column) {
                $permissions[] = $item_column['permission'];
            }
        }
    @endphp
    @if (backpack_user()->hasAnyPermission($permissions, config('backpack.base.guard')))
        <x-backpack::menu-dropdown :title="$item['title']" :icon="$item['icon']">
            @foreach ($item['columns'] as $column)
                <x-theme-tabler::menu-dropdown-column>
                    @php($permissions = array_column($column['items'], 'permission'))
                    @isset($column['title'])
                        @if (backpack_user()->hasAnyPermission($permissions, config('backpack.base.guard')))
                            <x-backpack::menu-dropdown-header :title="$column['title']"/>
                        @endif
                    @endisset
                    @foreach ($column['items'] as $item)
                        @if (backpack_user()->hasPermissionTo($item['permission'], config('backpack.base.guard')))
                            <x-backpack::menu-dropdown-item :title="$item['title']" :link="$item['link']"/>
                        @endif
                    @endforeach
                </x-theme-tabler::menu-dropdown-column>
            @endforeach
        </x-backpack::menu-dropdown>
    @endif
@endforeach
