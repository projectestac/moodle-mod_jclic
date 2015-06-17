// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Javascript helper function for Folder module
 *
 * @package    mod
 * @subpackage jclic
 * @copyright  2011 Departament d'Ensenyament de la Generalitat de Catalunya
 * @author     Sara Arjona Téllez <sarjona@xtec.cat>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

M.mod_jclic = {};

M.mod_jclic.init = function(Y, params) {
    console.log(params);
    setReporter('TCPReporter','path='+params['jclic_path']+';service='+params['jclic_service']+';user='+params['jclic_user']+';key='+params['id']+';lap='+params['jclic_lap']+';protocol='+params['jclic_protocol']);
    setSkin(params['skin']);
    setLanguage(params['lang']);
    setExitUrl(params['exiturl']);
    if (params['html5enabled'] && (isHTML5Installed() || !isJavaInstalled())){
        if (endsWith(params['jclic_url'],'.zip')) {
            params['jclic_url'] = params['jclic_url'].substring(0,params['jclic_url'].length - 4);
        }
        var dataoptions = {
            width: params['width'],
            height: params['height'],
        };
        //dataoptions.exitUrl = params['exiturl'];
        //dataoptions.lang = params['lang'];
        //dataoptions.skin = params['skin'];
        //dataoptions.TCPReporter = 'path='+params['jclic_path']+';service='+params['jclic_service']+';user='+params['jclic_user']+';key='+params['id']+';lap='+params['jclic_lap']+';protocol='+params['jclic_protocol'];
        var json = JSON.stringify(dataoptions);
        document.getElementById('jclic_applet').innerHTML = '<div class="JClic" data-project="'+params['jclic_url']+'" data-options=\''+json+'\'></div>';
    } else {
        setJarBase(params['jclic_jarbase']);
        document.getElementById('jclic_applet').innerHTML = getPlugin(params['jclic_url'], params['width'], params['height']);
    }

};

function endsWith(str, suffix) {
    return str.indexOf(suffix, str.length - suffix.length) !== -1;
}

function showSessionActivities(sessionid){
    activities = document.getElementById('session_'+sessionid);
    if (activities.className == 'jclic-session-activities-visible') {
        activities.className='jclic-session-activities-hidden';
    } else{
        activities.className='jclic-session-activities-visible';
    }
}

function isHTML5Installed() {
    if (isInternetExplorer()) {
        if ((views.is3D || html5CodebaseScript === "web3d.nocache.js") && getIEVersion() < 11) {
            return false;
        } else if (getIEVersion() < 10) {
            return false;
        }
    }
    return true;
}

function isJavaInstalled(){
    if (typeof deployJava === "undefined") {
        if (navigator.javaEnabled()) {
            if (isInternetExplorer() && getIEVersion() >= 10) {
                if (window.innerWidth === screen.width && window.innerHeight === screen.height) {
                    return false;
                }
            }
            if (navigator.userAgent.indexOf("Android ") > -1) {
                return false;
            }
            if (!isInternetExplorer() && !pluginEnabled("java")) {
                return false;
            }
            return true;
        }
    } else {
        return deployJava.versionCheck("1.6.0+") || deployJava.versionCheck("1.4") || deployJava.versionCheck("1.5.0*")
    }
}

function getIEVersion() {
    var a = navigator.appVersion;
    if (a.indexOf("Trident/7.0") > 0) return 11;
    else {
        return a.indexOf("MSIE") + 1 ? parseFloat(a.split("MSIE")[1]) : 999
    }
}
function isInternetExplorer() {
    return getIEVersion() != 999
}
function pluginEnabled(name) {
    var plugins = navigator.plugins,
        i = plugins.length,
        regExp = new RegExp(name, "i");
    while (i--) {
        if (regExp.test(plugins[i].name)) {
            return true;
        }
    }
    return false;
}