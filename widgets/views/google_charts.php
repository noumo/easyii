<?php
use yii\helpers\Url;
?>
<h2>
    <?= Yii::t('easyii', 'Statistics') ?>
    <div class="btn-group pull-right" role="group">
        <a href="<?= Url::current(['period' => 30]) ?>" type="button" class="btn btn-<?= ($period == 30 ? 'primary' : 'default') ?>"><?= Yii::t('easyii', 'Month') ?></a>
        <a href="<?= Url::current(['period' => 7]) ?>" type="button" class="btn btn-<?= ($period == 7 ? 'primary' : 'default') ?>"><?= Yii::t('easyii', 'Week') ?></a>
        <a href="<?= Url::current(['period' => '']) ?>" type="button" class="btn btn-<?= ($period ? 'default' : 'primary') ?>"><?= Yii::t('easyii', 'Today') ?></a>
    </div>
</h2>

<div class="active-users">
    <span class="pull-left img-circle online-circle"></span>
    <?= Yii::t('easyii', 'Active users') ?>:
    <b id="active-users-count"></b>
</div>

<br />

<table class="chart-table">
    <tr><th><?= Yii::t('easyii', 'Sessions and views') ?></th></tr>
    <tr><td><div id="chart-1-container"><br/><span class="small smooth"><?= Yii::t('easyii', 'chart is loading') ?>...</span></div></td></tr>
</table>

<br />

<div class="row">
    <div class="col-md-6">
        <table class="chart-table">
            <tr><th><?= Yii::t('easyii', 'Countries') ?></th></tr>
            <tr><td><div id="chart-2-container"><br/><span class="small smooth"><?= Yii::t('easyii', 'chart is loading') ?>...</span></div></td></tr>
        </table>
    </div>
    <div class="col-md-6">
        <table class="chart-table">
            <tr><th><?= Yii::t('easyii', 'Sources') ?></th></tr>
            <tr><td><div id="chart-3-container"><br/><span class="small smooth"><?= Yii::t('easyii', 'chart is loading') ?>...</span></div></td></tr>
        </table>
    </div>
</div>

<script>
    gapi.analytics.ready(function() {
        gapi.analytics.createComponent("ActiveUsers", {
            initialize: function() {
                this.activeUsers = 0
            },
            execute: function() {
                this.polling_ && this.stop(), this.render_(), gapi.analytics.auth.isAuthorized() ? this.getActiveUsers_() : gapi.analytics.auth.once("success", this.getActiveUsers_.bind(this))
            },
            stop: function() {
                clearTimeout(this.timeout_), this.polling_ = !1, this.emit("stop", {
                    activeUsers: this.activeUsers
                })
            },
            render_: function() {
                var t = this.get();
                this.container = "string" == typeof t.container ? document.getElementById(t.container) : t.container, this.container.innerHTML = this.activeUsers
            },
            getActiveUsers_: function() {
                var t = this.get(),
                    e = 1e3 * (t.pollingInterval || 5);
                if (isNaN(e) || 5e3 > e) throw new Error("Frequency must be 5 seconds or more.");
                this.polling_ = !0, gapi.client.analytics.data.realtime.get({
                    ids: t.ids,
                    metrics: "rt:activeUsers"
                }).execute(function(t) {
                    var i = t.totalResults ? +t.rows[0][0] : 0,
                        s = this.activeUsers;
                    this.emit("success", {
                        activeUsers: this.activeUsers
                    }), i != s && (this.activeUsers = i, this.onChange_(i - s)), (this.polling_ = !0) && (this.timeout_ = setTimeout(this.getActiveUsers_.bind(this), e))
                }.bind(this))
            },
            onChange_: function(t) {
                var e = this.container;
                e && (e.innerHTML = this.activeUsers), this.emit("change", {
                    activeUsers: this.activeUsers,
                    delta: t
                }), t > 0 ? this.emit("increase", {
                    activeUsers: this.activeUsers,
                    delta: t
                }) : this.emit("decrease", {
                    activeUsers: this.activeUsers,
                    delta: t
                })
            }
        });

        /**
         * Authorize the user with an access token obtained server side.
         */
        gapi.analytics.auth.authorize({
            'serverAuth': {
                'access_token': '<?= $access_token ?>'
            }
        });

        var activeUsers = new gapi.analytics.ext.ActiveUsers({
            ids: 'ga:<?= $this->context->ids ?>',
            container: 'active-users-count',
            pollingInterval: 10
        });

        var dataChart1 = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:<?= $this->context->ids ?>',
                'start-date': '<?= ($period ? $period . 'daysAgo' : 'today') ?>',
                'end-date': 'today',
                'metrics': 'ga:pageviews,ga:sessions',
                'dimensions': 'ga:<?= ($period ? 'date' : 'hour') ?>'
            },
            chart: {
                'container': 'chart-1-container',
                'type': 'LINE',
                'options': {
                    'width': '100%'
                }
            }
        });

        var dataChart2 = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:<?= $this->context->ids ?>',
                'start-date': '<?= ($period ? $period . 'daysAgo' : 'today') ?>',
                'end-date': 'today',
                'metrics': 'ga:sessions',
                'dimensions': 'ga:country',
                'sort': '-ga:sessions',
                'max-results': 7
            },
            chart: {
                'container': 'chart-2-container',
                'type': 'PIE',
                'options': {
                    'width': '50%',
                    'pieHole': 4/9
                }
            }
        });

        var dataChart3 = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:<?= $this->context->ids ?>',
                'start-date': '<?= ($period ? $period . 'daysAgo' : 'today') ?>',
                'end-date': 'today',
                'metrics': 'ga:sessions',
                'dimensions': 'ga:source',
                'sort': '-ga:sessions',
                'max-results': 7
            },
            chart: {
                'container': 'chart-3-container',
                'type': 'PIE',
                'options': {
                    'width': '50%',
                    'pieHole': 4/9
                }
            }
        });

        activeUsers.execute();
        dataChart1.execute();
        dataChart2.execute();
        dataChart3.execute();

    });
</script>