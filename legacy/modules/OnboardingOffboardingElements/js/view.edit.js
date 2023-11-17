let OnboardingOffboardingElementsViewEdit = {

   formSelector: $('form#EditView'),

   init: function () {
      this.bindEvents();
      this.setTypeList();
      this.setKindOfElementList();
   },
   bindEvents: function () {
      this.beforeSave();
      this.formSelector.find('#type').on('change', this.setKindOfElementList.bind(this));
   },
   beforeSave: function () {
      viewTools.form.beforeSave(function () {
         let result = this.validateTaskDuration();
         if (result === false) {
            viewTools.form.focusOnFirstError();
         }
         return result;
      }.bind(this));
   },
   validateTaskDuration: function () {
      let result = true;
      let task_duration_hours = this.formSelector.find('#task_duration_hours');
      let task_duration_minutes = this.formSelector.find('#task_duration_minutes');
      let task_duration_hours_val = parseInt(task_duration_hours.val());
      let task_duration_minutes_val = parseInt(task_duration_minutes.val());
      if (task_duration_hours_val < 0 || task_duration_minutes_val < 0) {
         result = false;
         viewTools.GUI.fieldErrorMark(task_duration_hours, viewTools.language.get('OnboardingOffboardingElements', 'LBL_ERR_NOTICE_DURATION_TIME'));
      }
      return result;
   },
   setTypeList: function () {
      let element_id = this.formSelector.find('input[type="hidden"][name="record"]').val();
      let relate_to = this.formSelector.find('input[type="hidden"][name="relate_to"]').val();
      let element_type_selector = this.formSelector.find('#type option[value=exit_interview]');
      if (typeof element_id !== 'undefined' && !_.isEmpty(element_id)) {
         viewTools.api.callCustomApi({
            module: 'OnboardingOffboardingElements',
            action: 'isRelatedToOnboardingTemplates',
            dataPOST: {
               element_id: element_id
            },
            callback: function (data) {
               if (data) {
                  element_type_selector.css('display', 'none');
               }
            }
         });
      } else if (typeof relate_to !== 'undefined' && relate_to === 'onboardingoffboardingelements_onboardingtemplates') {
         element_type_selector.css('display', 'none');
      }
   },
   setKindOfElementList: function () {
      let kindOfElementFieldSelector = this.formSelector.find('#kind_of_element');
      let kindOfElementSelfOptionSelector = this.formSelector.find('#kind_of_element option[value=self]');
      if (this.formSelector.find('#type').val() == 'task') {
         kindOfElementSelfOptionSelector.toggle(true);
      } else {
         kindOfElementSelfOptionSelector.toggle(false);
         if (kindOfElementFieldSelector.val() == 'self') {
            kindOfElementFieldSelector.val('');
         }
      }
   }
};

$(document).ready(OnboardingOffboardingElementsViewEdit.init.bind(OnboardingOffboardingElementsViewEdit));
