/**
 *     txt - html код подсказки, если не указан, то берется из title целевого элемента
 *     maxWidth - максимальная ширина всплывающего блока
 *     effect - эффект появления, может принимать значения: fadeIn (по умолчанию), slideDown, hide. Для затухания будет взят противоположный эффект.
 *         duration - длительность появления/затухания
 *         css:
 *         .tooltip {
 *         position: absolute;
 *         z-index: 1000;
 *         border: 1px solid gray;
 *         background-color: #FFFFD4;
 *         padding: 10px 10px 10px 10px;
 *         opacity: 0.85;
 *         filter: alpha(opacity=85);
 *         }
 *     use: $("#tst5").tooltip({
 *              txt: 'любой хтмл',
 *              effect: 'show',
 *              duration: 900
 *              });
 */
jQuery.fn.tooltip = function(options) {		
	var options = jQuery.extend({
		txt: '', 
		maxWidth: 300,
		effect: 'fadeIn',
		duration: 300
	},options);
	
	var helper,effect={},el_tips={};
	if(!$("div.tooltip").length) 
	$(function() {helper = $('<div class="tooltip"></div>').appendTo(document.body).hide();});
	else helper = $("div.tooltip").hide();
	
	effect.show = options.effect;
	switch(options.effect) {
		case 'fadeIn': 		effect.hide = 'fadeOut'; 	break;
		case 'show': 		effect.hide = 'hide'; 		break;
		case 'slideDown': 	effect.hide = 'slideUp'; 	break;
		default: 			effect.hide = 'fadeOut'; 	break;
	}
			
	return this.each(function() {
		if(options.txt) el_tips[$.data(this)] = options.txt;
			else el_tips[$.data(this)] = this.title;
			this.title = '';
			this.alt = '';
		}).mouseover(
			function () {
				if(el_tips[$.data(this)] != '') {
					helper.css('width','');
					helper.html(el_tips[$.data(this)]);
					if(helper.width() > options.maxWidth) helper.width(options.maxWidth);
					eval('helper.'+effect.show+'('+options.duration+')');
					$(this).bind('mousemove', update);
				}
			}
		).mouseout(
			function () {
				$(this).unbind('mousemove', update);	
				eval('helper.'+effect.hide+'('+options.duration+')');
			}		
		);
			

		function update(e) {		
			if (e.pageX + helper.width() + 40 > $(document).scrollLeft() + window.screen.availWidth) 
			helper.css({left: e.pageX - helper.width() - 25 + "px"});
			else helper.css({left: e.pageX + 5 + "px"});
		
			if (e.pageY - helper.height() - 25 < $(document).scrollTop()) helper.css({top: e.pageY + 25 + "px"});
			else helper.css({top: e.pageY - helper.height() - 25 + "px"});
		};
};