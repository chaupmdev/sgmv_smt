var g_ga_script = document.createElement('script'); //変数名は適当なものにでも
g_ga_script.async = true;
g_ga_script.src = "https://www.googletagmanager.com/gtag/js?id=G-Y9GGCN8FE4"; //ファイルパス
document.head.appendChild(g_ga_script);

var g_ga_now = new Date();
var g_ga_year = g_ga_now.getFullYear();
var g_ga_month = g_ga_now.getMonth()+1;
var g_ga_date = g_ga_now.getDate();
var g_ga_hour = g_ga_now.getHours();
var g_ga_min = g_ga_now.getMinutes();
var g_ga_sec = g_ga_now.getSeconds();

var g_ga_script3 = document.createElement('script'); //変数名は適当なものにでも
g_ga_script3.src = "/js/ga-script.js?"+g_ga_year+g_ga_month+g_ga_date+g_ga_hour+g_ga_min+g_ga_sec;  //ファイルパス
document.head.appendChild(g_ga_script3);
