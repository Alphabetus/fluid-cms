
window.changeLocale = function() {
    const $this = $(this);
    const url = $this.val();
    location.replace(url);
}