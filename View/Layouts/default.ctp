<!DOCTYPE html>
<html ng-app="boostApp">
    <head>
        <meta charset="utf-8">        
        <link rel="apple-touch-icon" sizes="180x180" href="<?= $this->webroot; ?>img/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?= $this->webroot; ?>img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="192x192" href="<?= $this->webroot; ?>/android-chrome-192x192.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?= $this->webroot; ?>img/favicon-16x16.png">
        <link rel="manifest" href="<?= $this->webroot; ?>/site.webmanifest">
        <link rel="mask-icon" href="<?= $this->webroot; ?>img/safari-pinned-tab.svg" color="#5bd5d5">
        <meta name="msapplication-TileColor" content="#00aba9">
        <meta name="theme-color" content="#ffffff">
        <meta name="msapplication-config" content="<?= $this->webroot; ?>img/browserconfig.xml">

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="Swag Tags, Plastic Dog Tags, Laminated Tags, Custom Dog Tags, imagestuff, plastic school tags, student tags, swagtags, Achievement tags, Recognition tags, school stickers,custom stickers, custom decals, school patches ">
        <meta name="author" content="Richard Madsen">
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1, minimum-scale=1.0, user-scalable=yes, maximum-scale=1.0, minimal-ui" name="viewport">
        <?= $this->Html->meta("description", $this->fetch("description")); ?>
        <?= $this->Html->meta("keywords", $this->fetch("keywords")); ?>

        <title>
            <?= $this->fetch("title") ?>
        </title>

        <!-- Load Angularjs first to insure best performance -->
        <?= $this->Html->script('angularjs/angular.min.js'); ?>
        <?= $this->Html->script('angularjs/angular-dirPaginate.js'); ?>
        <?= $this->Html->script('angularjs/angular-sanitize.js'); ?>

        <?= $this->Html->css('bootstrap.css'); ?>
        <?= $this->Html->css("font-awesome/css/font-awesome.min.css"); ?>
        <?= $this->Html->css("site.css"); ?>        
        <?= $this->fetch('css'); ?>

        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-7651007-9', 'auto');
            ga('send', 'pageview');

        </script>

    </head>
    <body ng-controller="MainCtrl">
        <!-- Facebook Pixel Code -->
        <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq)
                return;
            n = f.fbq = function () {
                n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq)
                f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window,
                document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1597569640546053'); // Insert your pixel ID here.
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
                       src="https://www.facebook.com/tr?id=1597569640546053&ev=PageView&noscript=1"
                       /></noscript>
        <!-- DO NOT MODIFY -->
        <!-- End Facebook Pixel Code -->

        <!-- Site Header -->
        <?php if (AuthComponent::user("customers_id")): ?>
            <?= $this->element("headings", ['headerData' => $headerData, 'subCategoriesGrp' => $subCategoriesGrp]) ?>
        <?php else: ?>
            <?= $this->element("headings") ?>
        <?php endif; ?>
        <?= $this->fetch("mainHeader"); ?>

        <!-- Main Content -->
        <?= $this->fetch("content"); ?>

        <!--  Modal Boxes -->
        <?= $this->element("modals"); ?>
        <?= $this->fetch("modalBoxes") ?>
        <?= $this->fetch("notifModal"); ?>

        <!-- Footer -->
        <?= $this->element("footers"); ?>
        <?= $this->fetch("mainFooter"); ?>

        <?= $this->Html->script("jquery-3.1.1.js"); ?>
        <?= $this->Html->script("bootstrap.min.js"); ?>
        <?= $this->Html->script("nav-highlighter.js"); ?>
        <?= $this->Html->script("site.js?v2.5"); ?>
        <?= $this->fetch('script'); ?>
    </body>
</html>
