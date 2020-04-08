let OnboardingOffboardingElementsViewQuickCreate = {

   formSelector: $('form#form_SubpanelQuickCreate_OnboardingOffboardingElements'),

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
      let parent_type = $('input[name="parent_type"]').val();
      let element_type_selector = this.formSelector.find('#type option[value=exit_interview]');
      if (parent_type == 'OnboardingTemplates') {
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

$(document).ready(OnboardingOffboardingElementsViewQuickCreate.init.bind(OnboardingOffboardingElementsViewQuickCreate));
