<form id="<%= form_name %>" name="<%= form_name %>">
    <b><%= APP.LBL_GENERATEONBOARDINGOFFBOARDING_TEMPLATE %>:</b>
    <div class="col-xs-12 col-sm-12 edit-view-field  yui-ac" type="parent" field="parent_name">
        <select name="parent_type" tabindex="0" id="parent_type" title="" class="vt_formulaSelector"
            onchange="document.<%= form_name %>.parent_name.value = &quot;&quot;;document.<%= form_name %>.parent_id.value = &quot;&quot;; changeParentQS( &quot;parent_name&quot; ); checkParentType( document.<%= form_name %>.parent_type.value, document.<%= form_name %>.btn_parent_name );"<%= hide_dropdown %>>
            <%= parent_type_options %>
        </select>
        <input type="text" name="parent_name" id="parent_name" class="vt_formulaSelector sqsEnabled yui-ac-input" tabindex="0" size="" autocomplete="off" value="<%= parent_name %>">
        <input type="hidden" class="vt_formulaSelector" name="parent_id" id="parent_id" value="<%= parent_id %>">
        <span class="id-ff multiple">
            <button type="button" name="btn_parent_name" id="btn_parent_name" tabindex="0" title="Wybierz"
                class="button firstChild" value="Wybierz"
                onclick="open_popup( <%= parent_type %>, 600, 400, &quot;&quot;, true, false, {&quot;call_back_function&quot;:&quot;viewTools.form.function.set_return&quot;,&quot;form_name&quot;:&quot;<%= form_name %>&quot;,&quot;field_to_name_array&quot;:{&quot;id&quot;:&quot;parent_id&quot;,&quot;name&quot;:&quot;parent_name&quot;}}, &quot;single&quot;, true );"><span
                    class="suitepicon suitepicon-action-select"></span></button><button type="button"
                name="btn_clr_parent_name" id="btn_clr_parent_name" tabindex="0" title="" class="button lastChild"
                onclick="this.form.parent_name.value = ''; this.form.parent_id.value = ''; $( '#parent_name' ).blur();"
                value=""><span class="suitepicon suitepicon-action-clear"></span></button>
        </span>
    </div>
    <br />
    <b><%= APP.LBL_GENERATEONBOARDINGOFFBOARDING_EMPLOYEE_NAME %>:</b>
    <div class="col-xs-12 col-sm-12 edit-view-field  yui-ac" type="relate" field="<%= relate_field_name %>_name">
        <input type="text" name="<%= relate_field_name %>_name" class="vt_formulaSelector sqsEnabled yui-ac-input" tabindex="" id="<%= relate_field_name %>_name" size="" value="<%= employee_name %>" title="" autocomplete="off">
        <input class="vt_formulaSelector" type="hidden" name="<%= relate_field_name %>_id" id="<%= relate_field_name %>_id" value="<%= employee_id %>">
        <span class="id-ff multiple">
            <button type="button" name="btn_<%= relate_field_name %>_name" id="btn_<%= relate_field_name %>_name" tabindex="" title="Wybierz użytkownika" class="button firstChild" value="Wybierz użytkownika" onclick="open_popup( '<%= relate_field_target_module %>', 600, 400, '&employee_status_advanced[]=Active', true, false,
                    {&quot;call_back_function&quot;:&quot;viewTools.form.function.set_return&quot;,&quot;form_name&quot;:&quot;<%= form_name %>&quot;,&quot;field_to_name_array&quot;:{&quot;id&quot;:&quot;<%= relate_field_name %>_id&quot;,&quot;name&quot;:&quot;<%= relate_field_name %>_name&quot;}}, 'single', true );">
                <img src="themes/SuiteP/images/id-ff-select.png">
            </button>
            <button type="button" name="btn_clr_<%= relate_field_name %>_name" id="btn_clr_<%= relate_field_name %>_name" tabindex="" title="Wyczyść użytkownika" class="button lastChild" onclick="SUGAR.clearRelateField( this.form, '<%= relate_field_name %>_name', '<%= relate_field_name %>_id' );
                   $( '#<%= relate_field_name %>_name,#<%= relate_field_name %>_id' ).blur();" value="Wyczyść użytkownika">
                <img src="themes/SuiteP/images/id-ff-clear.png">
            </button>
        </span>
    </div>
    <br />
    <b><%= APP.LBL_GENERATEONBOARDINGOFFBOARDING_START_DATE %>:</b>
    <div class="col-xs-12 col-sm-12 edit-view-field" type="datetimecombo" field="goo_date_start" style="margin-top: 7px">
        <table border="0" cellpadding="0" cellspacing="0" class="dateTime">
            <tbody>
                <tr valign="middle">
                    <td nowrap="" class="dateTimeComboColumn">
                        <input autocomplete="off" type="text" id="goo_date_start_date" class="datetimecombo_date" value="" size="11" maxlength="10" title="" tabindex="0" onblur="combo_goo_date_start.update();" onchange="combo_goo_date_start.update();">
                        <button type="button" id="goo_date_start_trigger" class="btn btn-danger" onclick="return false;">
                            <span class="suitepicon suitepicon-module-calendar" alt="Insert date"></span>
                        </button>
                    </td>
                    <td nowrap="" class="dateTimeComboColumn">
                        <div id="goo_date_start_time_section" class="datetimecombo_time_section"></div>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" class="DateTimeCombo" id="goo_date_start" name="goo_date_start" value="">
    </div>
</form>