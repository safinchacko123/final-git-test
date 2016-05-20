<h2>
    My Account <button class="btn btn-success pull-right account_logOutBtn" data-ng-click="showLogoutConfirmation()">Log Out</button>
</h2>
<hr>
<div class="col-md-offset-1 col-md-10 col-sm-12 col-xs-12">
    <div data-ng-class="{'col-sm-3':true, 'col-xs-3': true, 'text-center': true, account_activeTab: $state.includes('settings.addresses')}" class="col-sm-3 col-xs-3 text-center account_activeTab">
        <a data-ui-sref="settings.addresses" href="/settings/addresses"><h4>Addresses</h4></a>
    </div>
    <div data-ng-class="{'col-sm-3':true, 'col-xs-3': true, 'text-center': true, account_activeTab: $state.includes('settings.orders') || $state.includes('settings.order_details')}" class="col-sm-3 col-xs-3 text-center">
        <a data-ui-sref="settings.orders" href="/settings/orders"><h4>Order History</h4></a>
    </div>
    <div data-ng-class="{'col-sm-3':true, 'col-xs-3': true, 'text-center': true, account_activeTab: $state.includes('settings.promotions')}" class="col-sm-3 col-xs-3 text-center">
        <a data-ui-sref="settings.promotions" href="/settings/peppercash"><h4>Promotions</h4></a>
    </div>
</div>