M.mod_jclic = {};

M.mod_jclic.init = function(Y, params) {
    setReporter('TCPReporter','path='+params['jclic_path']+';service='+params['jclic_service']+';user='+params['jclic_user']+';key='+params['id']+';lap='+params['jclic_lap']+';protocol='+params['jclic_protocol']);
    setSkin(params['skin']);
    setLanguage(params['lang']);
    setExitUrl(params['exiturl']);
    document.getElementById('jclic_applet').innerHTML = getPlugin(params['jclic_url'], params['width'], params['height']);   
};