<button class="btn-link text-secondary border-none decoration-none shadow-none nav-link" data-bs-toggle="modal"
        data-bs-target="#modal-layout">
    <i class="la la-palette fs-2 m-0"></i>
</button>

@php
    $themes = [
        (object) [
            'value' => 'horizontal',
            'label' => trans('Horizontal'),
        ],
        (object) [
            'value' => 'horizontal_dark',
            'label' => trans('Horizontal Dark'),
        ],
        (object) [
            'value' => 'horizontal_overlap',
            'label' => trans('Horizontal Overlap'),
        ],
        (object) [
            'value' => 'vertical',
            'label' => trans('Vertical'),
        ],
        (object) [
            'value' => 'vertical_dark',
            'label' => trans('Vertical Dark'),
        ],
        (object) [
            'value' => 'vertical_transparent',
            'label' => trans('Vertical Transparent (Legacy theme)'),
        ],
        (object) [
            'value' => 'right_vertical',
            'label' => trans('Right Vertical'),
        ],
        (object) [
            'value' => 'right_vertical_dark',
            'label' => trans('Right Vertical Dark'),
        ],
        (object) [
            'value' => 'right_vertical_transparent',
            'label' => trans('Right Vertical Transparent'),
        ],
    ];
@endphp

@section('before_scripts')
    <div class="modal modal-blur fade pe-0" id="modal-layout" tabindex="-1" style="display: none;" aria-modal="false"
         role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <form method="POST" action="{{ route('tabler.switch.layout') }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans('Layouts') }}</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal"
                                aria-label="{{ trans('Close') }}"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div id="tabler-layouts-selection" class="form-selectgroup-boxes row mb-3">
                            @foreach ($themes as $theme)
                                <div class="col-lg-6 mb-2">
                                    <label class="form-selectgroup-item">
                                        <input @if (backpack_theme_config('layout') === $theme->value) checked
                                               @endif type="radio"
                                               name="layout" value="{{ $theme->value }}" class="form-selectgroup-input">
                                        <span class="form-selectgroup-label d-flex align-items-center p-3">
                                            <span class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </span>
                                            <span class="form-selectgroup-label-content">
                                                <span
                                                    class="form-selectgroup-title strong mb-1">{{ $theme->label }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn" data-dismiss="modal" data-bs-dismiss="modal">
                                {{ trans('Cancel') }}
                            </a>
                            <button class="btn btn-primary" type="submit">
                                <i class="la la-check me-2"></i>
                                {{ trans('Apply layout') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
