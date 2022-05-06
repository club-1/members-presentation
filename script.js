$(".member").click(function () {
    var name = this.dataset.id;
    $("#" + name).show();
});

$(".member").draggable({ grid: [80, 80] });


$(".presentation").draggable(function () {
    handle: ".handle";
});

$("div, p").disableSelection();


$(".content").resizable();

$(".close").click(function () {
    $(this).parent().hide();
});


/**
 * Manage messages comming into this window. 
 * @param {MessageEvent} event The message received.
 */
function handleMessageEvent(event) {
    if (!event.data.target) {
        return;
    }
    let elem = document.getElementById(event.data.target);
    if (!elem) {
        return;
    }
    elem.style.display = 'block';
}
window.addEventListener('message', handleMessageEvent)