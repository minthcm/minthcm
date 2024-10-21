async function changeNewsStatus(status) {
    if (status === 'published') {
        var news_id = $('form[name="DetailView"] input[type="hidden"][name="record"]').val();
        const hasTarget = await viewTools.api.asyncApiCall({
            module: 'News',
            action: 'hasNewsTarget',
            format: 'JSON',
            dataPOST: { news_id },
        });

        if (!hasTarget) {
            MintHCMPopup.alert(viewTools.language.get('News', 'LBL_NO_TARGET_MSG'));
            return;
        }

        const shouldSetStatus = await MintHCMPopup.confirm(viewTools.language.get('News', 'LBL_DIALOG_TEXT'))
        if (shouldSetStatus) setNewsStatus(status)

    } else if (status === 'archived') {
        setNewsStatus(status);
    }
}

async function setNewsStatus(status) {
    var news_id = $('form[name="DetailView"] input[type="hidden"][name="record"]').val();
    if (typeof news_id == 'undefined') {
        viewTools.GUI.statusBox.showStatus(viewTools.language.get('News', 'LBL_' + status.toUpperCase() + '_ERROR'), 'error', 2000);
        return
    }
    
    const data = await viewTools.api.asyncApiCall({
        module: 'News',
        action: 'setNewsStatus',
        format: 'JSON',
        dataPOST: { 
            news_id: news_id,
            status: status
         },
    });

    if (data) {
        location.reload();
    } else {
        viewTools.GUI.statusBox.showStatus(viewTools.language.get('News', 'LBL_' + status.toUpperCase() + '_ERROR'), 'error', 2000);
    }
}
