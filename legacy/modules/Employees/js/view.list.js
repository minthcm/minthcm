// MintHCM #107081 START 
// Order ascending by last name if data isn't sorted by any field
if (!document.querySelector('.suitepicon-action-sorting-descending')
    && !document.querySelector('.suitepicon-action-sorting-ascending')
) {
    sListView.order_checks('ASC', 'last_name', 'Employees2_EMPLOYEE_ORDER_BY');
}
// MintHCM #107081 END
