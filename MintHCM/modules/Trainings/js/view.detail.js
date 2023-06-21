let TrainingsDetailViewTimer = 0;
if (typeof TrainingsDetailView !== 'function') {
    class TrainingsDetailView {
        constructor() {
            this.id = window.document.forms['DetailView'].elements['record'].value;
        }
        closeTraining(){
            viewTools.api.callCustomApi({
                module: "Trainings",
                action: 'closeTraining',
                dataPOST: {
                    id: window.TrainingsDetailView.id,
                },
                callback: function () {
                    document.location.reload();
                }
            });
        }
    }
    if (typeof window.TrainingsDetailView == 'undefined') {
        window.TrainingsDetailView = new TrainingsDetailView();
    }
}

$(document).ready(function () {
    TrainingsDetailViewTimer = setInterval(function () {
        if (
            typeof (moment) == 'function'
            && typeof (viewTools) == 'object'
        ) {
            clearInterval(TrainingsDetailViewTimer);
        }
    }, 100);
});
