var util = require('we7/resource/js/util.js');
App({
    onLaunch: function (res) {
    },
    onShow: function (res) {
    },
    onHide: function () {
    },
    onError: function (msg) {
        console.log(msg)
    },
    //加载微擎工具类
    util: util,
    //导航菜单，微擎将会自己实现一个导航菜单，结构与小程序导航菜单相同
    //用户信息，sessionid是用户是否登录的凭证
    userInfo: {
        sessionid: null,
    },
    "tabBar": {
        "color": "#999",
        "selectedColor": "#f86b4f",
        "borderStyle": "#fff",
        "backgroundColor": "#fff",
        "list": [
            {
                "pagePath": "/openmouth_eat/pages/store/index",
                "iconPath": "/openmouth_eat/resource/icon/home.png",
                "selectedIconPath": "/openmouth_eat/resource/icon/home-selected.png",
                "text": "首页"
            },
            {
                "pagePath": "/openmouth_eat/pages/category/category",
                "iconPath": "/openmouth_eat/resource/icon/category.png",
                "selectedIconPath": "/openmouth_eat/resource/icon/category-selected.png",
                "text": "分类"
            },
            {
                "pagePath": "/openmouth_eat/pages/home/index",
                "iconPath": "/openmouth_eat/resource/icon/user.png",
                "selectedIconPath": "/openmouth_eat/resource/icon/user-selected.png",
                "text": "我的"
            }
        ]
    },
    siteInfo: require('siteinfo.js')
});