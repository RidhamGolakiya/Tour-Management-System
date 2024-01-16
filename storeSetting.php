<?php
include_once "./config.php";
if ($con) {
    echo "<script>let settings = localStorage.getItem('settings');
    let site_name = '" . $_SESSION['site_name'] . "';
    let logo = '" . $_SESSION['logo'] . "';
    let favicon = '" . $_SESSION['favicon'] . "';
    let themeColor = '" . $_SESSION['themeColor'] . "';
    let appUrl = '$appUrl';

        settings = settings && JSON.parse(settings);
        localStorage.setItem('settings', JSON.stringify({
            ...settings,
            site_name: site_name,
            logo: logo,
            favicon:favicon,
            appUrl:appUrl,
            themeColor:themeColor}));
</script>";
}
?>