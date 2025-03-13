document.addEventListener('DOMContentLoaded', () => {
    if($('[data-newspage-type="single"]').length === 1){
        checkCategory();
        checkArchive();
    }

    if($('[data-newspage-type="category"]').length === 1){
        checkCategory();
    }

    if($('[data-newspage-type="archive"]').length === 1){
        checkArchive();
    }

});

$(function(){
	$('#gHeader .hBox #gNavi .hLinkList > li').eq(4).addClass('current');
});

function checkCategory(){
	// Category
	const dateCategory = $('#conts .titleBox .time').text();
	if(dateCategory) {
		$('#sideBar .sNavi[data-archive-type*="category"] li').each( (index, el) => {
			const regExp = new RegExp(`.*(${$(el).text()}).*`);
			if( dateCategory.match(regExp) ) $(el).addClass('on');
		});
	}
}

function checkArchive(){
	// Archive
	const dateCategory = $('#conts .titleBox .time').text();
	if(dateCategory) {
		let Ary = dateCategory.split('.');
		$('#sideBar .sNavi[data-archive-type*="archive"] li').each( (index, el) => {
			const regExp = new RegExp(`.*(${Ary[0]}).*`);
			if( $(el).text().match(regExp) ) $(el).addClass('on');
		});
	}
}