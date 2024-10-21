let KudosQuickCreateViewTimer = 0;
if (typeof KudosQuickCreateView !== 'function') {
    class KudosQuickCreateView extends KudosCommonView {
        constructor() {
            super();
            this.form = 'EditView';
        }
        init() {
            super.init();
        }
    }
    if (typeof window.KudosCreateView == 'undefined') {
        window.KudosQuickCreateView = new KudosQuickCreateView();
    }
}
$(document).ready(function () {
    KudosQuickCreateViewTimer = setInterval(function () {
        if (typeof moment == 'function' && typeof viewTools == 'object') {
            clearInterval(KudosQuickCreateViewTimer);
            window.KudosQuickCreateView.init();
        }
    }, 100);
});
