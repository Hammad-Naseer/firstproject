function nospaces(e){e.value.match(/\s/g)&&(e.value=e.value.replace(/\s/g,""))}$.fn.pageMe=function(e){var i=$.extend({perPage:7,showPrevNext:!1,numbersPerPage:1,hidePageNumbers:!1},e),a=this,n=i.perPage,r=a.children(),l=$(".pagination");void 0!==i.childSelector&&(r=a.find(i.childSelector)),void 0!==i.pagerSelector&&(l=$(i.pagerSelector));var s=r.length,t=Math.ceil(s/n),d=0;for(l.data("curr",d),i.showPrevNext&&$('<li><a href="#" class="prev_link">«</a></li>').appendTo(l);t>d&&0==i.hidePageNumbers;)$('<li><a href="#" class="page_link">'+(d+1)+"</a></li>").appendTo(l),d++;function c(e){var a=e*n,s=a+n;r.css("display","none").slice(a,s).show(),e>=1?l.find(".prev_link").show():l.find(".prev_link").hide(),e<t-1?l.find(".next_link").show():l.find(".next_link").hide(),l.data("curr",e),i.numbersPerPage>1&&($(".page_link").hide(),$(".page_link").slice(e,i.numbersPerPage+e).show()),l.children().removeClass("active"),l.children().eq(e+1).addClass("active")}i.numbersPerPage>1&&($(".page_link").hide(),$(".page_link").slice(l.data("curr"),i.numbersPerPage).show()),i.showPrevNext&&$('<li><a href="#" class="next_link">»</a></li>').appendTo(l),l.find(".page_link:first").addClass("active"),l.find(".prev_link").hide(),t<=1&&l.find(".next_link").hide(),l.children().eq(0).addClass("active"),r.hide(),r.slice(0,n).show(),l.find("li .page_link").click(function(){return c($(this).html().valueOf()-1),!1}),l.find("li .prev_link").click(function(){return c(parseInt(l.data("curr"))-1),!1}),l.find("li .next_link").click(function(){return goToPage=parseInt(l.data("curr"))+1,c(goToPage),!1})},$(document).ready(function(){$("#accordion").pageMe({pagerSelector:"#myPager",childSelector:".panel",showPrevNext:!0,hidePageNumbers:!1,perPage:20}),$("form").on("submit",function(){var e=$(this).attr("id");"filter"!=e&&($("#"+e).valid()&&($(this).find(":submit").attr("disabled","disabled"),$(this).css("cursor","not-allowed")))})});