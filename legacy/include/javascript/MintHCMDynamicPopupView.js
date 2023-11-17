var MintHCMDynamicPopupView = {
    popup : null,
    init : function (title, module_name, record_id, options) {
        viewTools.cache.form_beforeSave_enforced = [];
        let viewtype = 'EditView';
        let formname = 'EditView';
        this.options = options || {};
        if(this.options.isDetailView || false){
            viewtype = 'DetailView';
            formname = 'DetailView';
        }
        else{
            this.options.isDetailView = false;
        }
        let url = 'index.php?module='+module_name+'&action='+viewtype;
        if(!!record_id){
            url = url + '&record='+record_id;
        }
        if (!!options && !!options.fields) {
            for (const field in options.fields) {
                url += '&' + field + '=' + options.fields[field];
            }
        }
        this.go(url);

    this.id = "MintHCMDynamicPopupView";
    this.title = title || "";
    this.module_name = module_name || "";
    this.record_id = record_id || [];
    this.viewtype = viewtype ;
    this.formname = formname ;

    this.save = function(){
        viewTools.GUI.fieldErrorUnmark();
        let validation_is_ok = true;
        for (let key = 0; key < viewTools.cache.form_beforeSave_enforced.length; key++) {
            var tmp_function = viewTools.cache.form_beforeSave_enforced[key];
            if (tmp_function(this.formname) === false) {
                validation_is_ok = false;
                viewTools.form.error_count++;
            }
        }
        var _form = document.getElementById(this.formname);
        _form.action.value = 'Save';
        validation_is_ok = check_form(this.formname) && validation_is_ok;
        if (validation_is_ok) {
            MintHCMDynamicPopupView.submitForm(_form);
        } else {
            viewTools.form.focusOnFirstError();
            viewTools.form.onValidationEnd();
        }
    }

    this.close = function(){
        MintHCMDynamicPopupView.popup =null;
        window.onbeforeunload = null;
        MintHCMPopup.close();
    }


    this.loadingWindow = false;
    },

    callback : function(o)
    {
        var cont;
        if (typeof window.onbeforeunload == "function")
            window.onbeforeunload = null;
        scroll(0,0);
        try{
            var r = YAHOO.lang.JSON.parse(o.responseText);
            cont = r.content;
            let buttons =  [];
            if(!this.options.isDetailView){
                buttons.push({
                    text: viewTools.language.get('app_strings', 'LBL_SAVE_BUTTON_LABEL'),
                    class: "primary",
                    accesskey: "a",
                    click: this.save.bind(this),
                    primary: true,
                 });
            }
            buttons.push({
                text: viewTools.language.get('app_strings', 'LBL_CANCEL_BUTTON_LABEL'),
                class: "",
                accesskey: "l",
                click: this.close.bind(this),
                left: true,
             });
             if(this.options.isDetailView){
                cont = cont.replace(/\#content /g,".MintHCMPopup-body ");
             }
            this.popup = MintHCMPopup(
                this.title,
                cont,
                buttons,
                {
                  css: {'min-width': window.innerWidth *.7}
                },
                this.options.onShow || null
             );

            $('.MintHCMPopup > .MintHCMPopup-container').css({"min-width": "70vw","max-height": "100vh"});
            $('.MintHCMPopup .MintHCMPopup-body').css({"max-height":"70vh"});
            $('.MintHCMPopup .MintHCMPopup-buttons').css({"min-height":"5vh"});

            let found_form_name = $('.MintHCMPopup .MintHCMPopup-body form').attr('name');
            if(found_form_name!= undefined){
                this.formname = found_form_name;
            }

            SUGAR.util.evalScript(cont);
            // all javascripts have been processed - show content of placeholder

            this.hideLoadingPanel();
        } catch (e){
            this.hideLoadingPanel();
            this.showErrorMessage(o.responseText);
        }
        SUGAR_callsInProgress--;
    },
    showErrorMessage : function(errorMessage)
    {
        viewTools.GUI.statusBox.showStatus(errorMessage, 'error',5000);
        throw "AjaxUI error parsing response - " + errorMessage;
    },
    canAjaxLoadModule : function(module)
    {
        var checkLS = /&LicState=check/.exec(window.location.search);

        // Return false license state is set to check
        if( checkLS ){
            return false;
        }

        var bannedModules = SUGAR.config.stockAjaxBannedModules;
        //If banned modules isn't there, we are probably on a page that isn't ajaxUI compatible
        if (typeof(bannedModules) == 'undefined')
            return false;
        // Mechanism to allow for overriding or adding to this list
        if(typeof(SUGAR.config.addAjaxBannedModules) != 'undefined'){
            bannedModules.concat(SUGAR.config.addAjaxBannedModules);
        }
        if(typeof(SUGAR.config.overrideAjaxBannedModules) != 'undefined'){
            bannedModules = SUGAR.config.overrideAjaxBannedModules;
        }

        return SUGAR.util.arrayIndexOf(bannedModules, module) == -1;
    },

    go : function(url)
    {

        if(YAHOO.lang.trim(url) != "")
        {
            var con = YAHOO.util.Connect, ui = MintHCMDynamicPopupView;

          // ajaxUILoc XSS protection:
          // window.location = url; is vulnerable to XSS attack
          // Check for valid url
          // Expects url encoded versions of index.php?module=Home&action=index&parentTab=All
          var testUrl = decodeURIComponent(url);
          if (
            /^index.php?(([A-Z]|[a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+[%=+#&\[\]\.]*)+/i.test(testUrl) === false
          ) {
            throw "Invalid url";
          }


            if (typeof (window.onbeforeunload) == "function" && window.onbeforeunload())
            {
                //If there is an unload function, we need to check it ourselves
                if (!confirm(window.onbeforeunload()))
                {

                }
                window.onbeforeunload = null;
            }
            if (ui.lastCall && con.isCallInProgress(ui.lastCall)) {
                con.abort(ui.lastCall);
            }
            var mRegex = /module=([^&]*)/.exec(url);
            var module = mRegex ? mRegex[1] : false;
            //If we can't ajax load the module (blacklisted), set the URL directly.
            if (!this.canAjaxLoadModule(module)) {
              window.location = url;
            }
            ui.lastURL = url;
            this.cleanGlobals();
            var loadLanguageJS = '';
            if(module && typeof(SUGAR.language.languages[module]) == 'undefined'){
                loadLanguageJS = '&loadLanguageJS=1';
            }


            SUGAR_callsInProgress++;
            this.showLoadingPanel();
            ui.lastCall = YAHOO.util.Connect.asyncRequest('GET', url + '&minthcm_popup=1&ajax_load=1' + loadLanguageJS, {
                success: this.callback.bind(this),
                failure: function(){
                    SUGAR_callsInProgress--;
                    this.hideLoadingPanel();
                    this.showErrorMessage(viewTools.language.get('app_strings','LBL_ERROR_LOADING_DYNAMIC_POPUP'));
                }.bind(this)
            });

        }
    },

    submitForm : function(formname, params)
    {
        var con = YAHOO.util.Connect, SA = this;
        if (SA.lastCall && con.isCallInProgress(SA.lastCall)) {
            con.abort(SA.lastCall);
        }
        //Reset the EmailAddressWidget before loading a new page
        SA.cleanGlobals();
        this.showLoadingPanel();
        var form = YAHOO.util.Dom.get(formname) || document.forms[formname];
        var string = con.setForm(form);
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: string,
            success: function(msg) {
                this.hideLoadingPanel();
                MintHCMDynamicPopupView.popup =null;
                window.onbeforeunload = null;
                MintHCMPopup.close();
                YAHOO.util.Connect.resetFormState();
                viewTools.GUI.statusBox.showStatus(viewTools.language.get('app_strings','LBL_SAVED'), 'success',5000);
                if(typeof this.options.postSaveCallback == 'function'){
                    this.options.postSaveCallback();
                }

            }.bind(this),
            fail: function(msg) {
                YAHOO.util.Connect.resetFormState();
                this.hideLoadingPanel();
                this.showErrorMessage(viewTools.language.get('app_strings','LBL_ERROR_LOADING_DYNAMIC_POPUP_SAVE_ERROR'));
            }.bind(this)
        });
    },

    cleanGlobals : function()
    {
        sqs_objects = {};
        QSProcessedFieldsArray = {};
        collection = {};
        //Reset the EmailAddressWidget before loading a new page
        if (SUGAR.EmailAddressWidget){
            SUGAR.EmailAddressWidget.instances = {};
            SUGAR.EmailAddressWidget.count = {};
        }
        YAHOO.util.Event.removeListener(window, 'resize');
        //Hide any connector dialogs
        if(typeof(dialog) != 'undefined' && typeof(dialog.destroy) == 'function'){
            dialog.destroy();
            delete dialog;
        }

    },
    showLoadingPanel : function()
    {
        if (!this.loadingPanel)
        {
            this.loadingPanel = new YAHOO.widget.Panel("ajaxloading",
            {
                width:"240px",
                fixedcenter:true,
                close:false,
                draggable:false,
                constraintoviewport:false,
                modal:true,
                visible:false
            });
            this.loadingPanel.setBody('<div id="loadingPage" align="center" style="vertical-align:middle;"><img src="' + SUGAR.themes.loading_image + '" align="absmiddle" /> <b>' + SUGAR.language.get('app_strings', 'LBL_LOADING_PAGE') +'</b></div>');
            this.loadingPanel.render(document.body);
        }

        if (document.getElementById('ajaxloading_c'))
            document.getElementById('ajaxloading_c').style.display = '';

            this.loadingPanel.show();

    },
    hideLoadingPanel : function()
    {
        this.loadingPanel.hide();

        if (document.getElementById('ajaxloading_c'))
            document.getElementById('ajaxloading_c').style.display = 'none';
    }
};
