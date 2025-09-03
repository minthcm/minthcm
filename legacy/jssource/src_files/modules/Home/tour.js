/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */


var rtl = rtl == "undefined" ? false : rtl;
var modals=new Array();
modals[0] = {
	target: "#moduleTab_AllHome", 
	title: SUGAR.language.get('Home', 'LBL_TOUR_HOME'),
	content: SUGAR.language.get('Home', 'LBL_TOUR_HOME_DESCRIPTION'),
	placement: "bottom"
};
modals[1] = {
	target: "#moduleTabExtraMenuAll", 
	title: SUGAR.language.get('Home', 'LBL_TOUR_MORE'),
	content: SUGAR.language.get('Home', 'LBL_TOUR_MORE_DESCRIPTION'),
	placement: "bottom"
};
modals[2] = {
	target: "#dcmenuSearchDiv", 
	title: SUGAR.language.get('Home', 'LBL_TOUR_SEARCH'),
	content: SUGAR.language.get('Home', 'LBL_TOUR_SEARCH_DESCRIPTION'),
	placement: "bottom"
};
modals[3] = {
	target: $("#dcmenuSugarCube").length == 0 ? "#dcmenuSugarCubeEmpty" : "#dcmenuSugarCube",
	title: SUGAR.language.get('Home', 'LBL_TOUR_NOTIFICATIONS'),
	content: SUGAR.language.get('Home', 'LBL_TOUR_NOTIFICATIONS_DESCRIPTION'),
	placement: "bottom"
};
modals[4] = {
	target: "#globalLinksModule", 
	title: SUGAR.language.get('Home', 'LBL_TOUR_PROFILE'),
	content: SUGAR.language.get('Home', 'LBL_TOUR_PROFILE_DESCRIPTION'),
	placement: "bottom"
};
modals[5] = {
	target: "#quickCreate",
	title: SUGAR.language.get('Home', 'LBL_TOUR_QUICKCREATE'),
	content: SUGAR.language.get('Home', 'LBL_TOUR_QUICKCREATE_DESCRIPTION'),
	placement: "bottom right",
    leftOffset: rtl ? -40 : 40,
    topOffset: -10
};
modals[6] = {
	target: "#arrow",
	title: SUGAR.language.get('Home', 'LBL_TOUR_FOOTER'),
	content: SUGAR.language.get('Home', 'LBL_TOUR_FOOTER_DESCRIPTION'),
	placement: "top right",
    leftOffset: rtl ? -90 : 80,
    topOffset: -40
};
modals[7] = {
	target: "#integrations", 
	title: SUGAR.language.get('Home', 'LBL_TOUR_CUSTOM'),
	content: SUGAR.language.get('Home', 'LBL_TOUR_CUSTOM_DESCRIPTION'),
	placement: "top",
    leftOffset: rtl ? 30 : -30
};
modals[8] = {
	target: "#logo", 
	title: SUGAR.language.get('Home', 'LBL_TOUR_BRAND'),
	content: SUGAR.language.get('Home', 'LBL_TOUR_BRAND_DESCRIPTION'),
	placement: "top"
};



$(document).ready(function() {
	SUGAR.tour.init({
		id: 'tour',
		modals: modals,
		modalUrl: "index.php?module=Home&action=tour&to_pdf=1",
		prefUrl: "index.php?module=Users&action=UpdateTourStatus&to_pdf=true&viewed=true",
        className: 'whatsnew',
		onTourFinish: function() {
				$('#bootstrapJs').remove();
				$('#popoverext').remove();
				$('#bounce').remove();
				$('#bootstrapCss').remove();
				$('#tourCss').remove();
				$('#tourJs').remove();
				$('#whatsNewsJs').remove();
			}
		});	
});
	