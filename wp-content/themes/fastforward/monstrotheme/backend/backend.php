<div id="monstrotheme-backend-settings" class="bootstrap-inside" data-ng-app="MonstroThemeBackendSettings" data-ng-controller="MonstroThemeBackendSettingsCtrl">
    <div class="container">
        <h1 class="page-header">{{__('Monstrotheme settings')}}</h1>
            <ul class="col-md-3 nav nav-pills nav-stacked">
                <li data-ui-sref-active="active">
                    <a data-ui-sref="sidebarBuilder">{{__('Sidebar builder')}}</a>
                </li>
            </ul>
        <div class="col-md-9" data-ui-view="settingsView">

        </div>
    </div>
</div>