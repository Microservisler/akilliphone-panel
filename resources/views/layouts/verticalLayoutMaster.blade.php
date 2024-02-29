<!-- BEGIN: Header-->
@include('panels.navbar')
<!-- END: Header-->

<!-- BEGIN: Main Menu-->
@if((isset($configData['showMenu']) && $configData['showMenu'] === true))
    @include('panels.sidebar')
@endif
<!-- END: Main Menu-->

<!-- BEGIN: Content-->
<div class="app-content content {{ $configData['pageClass'] }}">
    <!-- BEGIN: Header-->
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    @if(validateRole(isset($validRole)?$validRole:null))
        @if(($configData['contentLayout']!=='default') && isset($configData['contentLayout']))
            <div class="content-area-wrapper {{ $configData['layoutWidth'] === 'boxed' ? 'container-xxl p-0' : '' }}">
                <div class="{{ $configData['sidebarPositionClass'] }}">
                    <div class="sidebar">
                        {{-- Include Sidebar Content --}}
                        @yield('content-sidebar')
                    </div>
                </div>
                <div class="{{ $configData['contentsidebarClass'] }}">
                    <div class="content-wrapper">
                        <div class="content-body">
                            {{-- Include Page Content --}}
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="content-wrapper {{ $configData['layoutWidth'] === 'boxed' ? 'container-xxl p-0' : '' }}">
                {{-- Include Breadcrumb --}}
                @if($configData['pageHeader'] === true && isset($configData['pageHeader']))
                    {{--      @include('panels.breadcrumb')--}}
                @endif

                <div class="content-body">
                    @yield('content')
                </div>
            </div>
        @endif
    @else
<p>Bu alan için yetkili değilsiniz</p>
    @endif

</div>
<!-- End: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

{{-- include footer --}}
@include('panels/footer')
{{-- include default scripts --}}
@include('panels/scripts')
@include('webservice-js')
<script src="{{ url('js/akilliphone.js') }}?_v={{ time() }}"></script>
<script src="{{ url('js/tulpar-uploader.js') }}?_v={{ time() }}"></script>
@yield('dataTable-script')
@yield('editor-script')
@yield('page-script')
<script type="text/javascript">
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14, height: 14
            });
        }
    })
</script>

