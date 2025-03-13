document.addEventListener('DOMContentLoaded', () => {
    const url_cml = location.pathname;
    $('#sideBar .sNavi li').each( (index, el) => {
        const regExp = new RegExp(`.*(${$(el).find('a').attr('href')}).*`);
        if( url_cml.match(regExp) ) $(el).addClass('on');
    });
});