
Ext.define('SpiceCRM.KReporter.Designer.visualizationplugins.googlechartspanel', {
    extend: 'Ext.form.Panel',
    //layout: 'fit',
    layout: 'fit',
    height: 400,
    border: false,
    chartData: {},
    chartIndex: 0,
    padding: 5,
    items: [
        {
            xtype: 'panel',
            frame: false,
            border: false,
            padding: 5,
            items: [
                {
                    xtype: 'fieldset',
                    collapsible: false,
                    title: languageGetText('LBL_CHARTFS_TYPE'),
                    // width: 'calc (100% - 5px)',
                    layout: 'hbox',
                    defaults: {
                        padding: '0px 10px'
                    },
                    items: [
                        {
                            xtype: 'panel',
                            flex: 1,
                            border: false,
                            layout: 'vbox',
                            defaults: {
                                width: '100%'
                            },
                            items: [
                                {
                                    xtype: 'combo',
                                    itemId: 'chartdimension',
                                    readOnly: true,
                                    value: '',
                                    fieldLabel: languageGetText('LBL_DIMENSIONS'),
                                    store: new Ext.data.Store({
                                        storeId: 'kreportdesginerGoogleChartsDimensionsStore',
                                        fields: ['dimension', 'label'],
                                        data: [{
                                            'dimension': '111',
                                            'label': languageGetText('LBL_DIMENSION_111')
                                        }]
                                    }),
                                    queryMode: 'local',
                                    disabled: false,
                                    editable: false,
                                    displayField: 'label',
                                    valueField: 'dimension',
                                    listeners: {
                                        select: function (thisCombo) {
                                            var _chartTypeCombo = this.up('fieldset').down('#charttype');
                                            var _chartTypesStore = Ext.data.StoreManager.lookup('kreportdesignerGoogleChartsCrattypesStore');
                                            _chartTypesStore.clearFilter();
                                            _chartTypesStore.filter('dimension', new RegExp(thisCombo.getValue()));
                                            if (_chartTypeCombo.getValue() !== '' && _chartTypesStore.count() > 0) {
                                                if (_chartTypesStore.findRecord('charttype', _chartTypeCombo.getValue()) === null) {
                                                    _chartTypeCombo.enable();
                                                    _chartTypeCombo.setValue(_chartTypesStore.getAt(0).get('charttype'));
                                                }
                                            }
                                            else if (_chartTypesStore.count() > 0) {
                                                _chartTypeCombo.enable();
                                                _chartTypeCombo.setValue(_chartTypesStore.getAt(0).get('charttype'));
                                            }
                                            else {
                                                _chartTypeCombo.disable();
                                                _chartTypeCombo.setValue();
                                            }

                                        }
                                    },
                                    setInitialValue: function (value) {
                                        this.setValue(value);
                                        this.fireEvent('select', this);
                                    },
                                }, {
                                    fieldLabel: languageGetText('LBL_CHARTTYPE_COLORS'),
                                    xtype: 'combo',
                                    itemId: 'colorCombo',
                                    flex: 1,
                                    store: Ext.data.StoreManager.lookup('KReportDesginerHighChartsColorStore'),
                                    valueField: 'id',
                                    displayField: 'name',
                                    typeAhead: false,
                                    editable: false,
                                    queryMode: 'local',
                                    triggerAction: 'all',
                                    value: 'default',
                                    listeners: {
                                        select: function () {
                                            this.up('fieldset').down('displayfield[itemId=colorDisplay]').updateColors();
                                        }
                                    }
                                }]
                        }, {
                            xtype: 'panel',
                            flex: 1,
                            border: false,
                            layout: 'vbox',
                            defaults: {
                                width: '100%'
                            },
                            items: [
                                {
                                    xtype: 'combo',
                                    itemId: 'charttype',
                                    fieldLabel: languageGetText('LBL_CHARTTYPES'),
                                    store: new Ext.data.Store({
                                        storeId: 'kreportdesignerGoogleChartsCrattypesStore',
                                        fields: ['dimension', 'charttype', 'label'],
                                        data: [{
                                            'dimension': '111',
                                            'charttype': 'Area',
                                            'label': languageGetText('LBL_CHARTTYPE_AREA')
                                        }, {
                                            'dimension': '111',
                                            'charttype': 'SteppedArea',
                                            'label': languageGetText('LBL_CHARTTYPE_STEPPEDAREA')
                                        }, {
                                            'dimension': '111',
                                            'charttype': 'Bar',
                                            'label': languageGetText('LBL_CHARTTYPE_BAR')
                                        }, {
                                            'dimension': '111',
                                            'charttype': 'Column',
                                            'label': languageGetText('LBL_CHARTTYPE_COLUMN')
                                        }, {
                                            'dimension': '111',
                                            'charttype': 'Line',
                                            'label': languageGetText('LBL_CHARTTYPE_LINE')
                                        }, {
                                            'dimension': '111',
                                            'charttype': 'Pie',
                                            'label': languageGetText('LBL_CHARTTYPE_PIE')
                                        }, {
                                            'dimension': '111',
                                            'charttype': 'Donut',
                                            'label': languageGetText('LBL_CHARTTYPE_DONUT')
                                        }]
                                    }),
                                    value: '',
                                    queryMode: 'local',
                                    editable: false,
                                    displayField: 'label',
                                    valueField: 'charttype'
                                }, {
                                    xtype: 'displayfield',
                                    fieldLabel: languageGetText('LBL_CHARTTYPE_COLORPREVIEW'),
                                    flex: 1,
                                    itemId: 'colorDisplay',
                                    updateColors: function () {
                                        var colorId = this.up('fieldset').down('combo[itemId=colorCombo]').getValue();
                                        if (colorId !== null) {
                                            var colorArray = Ext.data.StoreManager.lookup('KReportDesginerHighChartsColorStore').findRecord('id', colorId).get('colors').split('*');
                                            var colorString = '';
                                            for (var i = 0; i < colorArray.length; i++)
                                                colorString = colorString + '<div style="border:1px solid #ddd;float:left;margin-left:5px;width:16px;height:16px;background:' + colorArray[i] + '"></div>';
                                            this.setRawValue(colorString);
                                        }
                                        else
                                            this.setRawValue('');
                                    }
                                }]
                        }]
                }, {
                    xtype: 'fieldset',
                    collapsible: false,
                    title: languageGetText('LBL_CHARTFS_DATA'),
                    items: [
                        {
                            xtype: 'panel',
                            border: false,
                            layout: 'hbox',
                            defaults: {
                                padding: 10
                            },
                            items: [
                                {
                                    xtype: 'panel',
                                    border: false,
                                    layout: 'vbox',
                                    flex: 1,
                                    defaults: {
                                        width: '100%'
                                    },
                                    items: [
                                        {
                                            xtype: 'combo',
                                            itemId: 'dimension1',
                                            editable: false,
                                            border: 1,
                                            fieldLabel: languageGetText('LBL_CHARTTYPE_DIMENSION1'),
                                            triggerAction: 'all',
                                            lazyRender: true,
                                            queryMode: 'local',
                                            store: Ext.data.StoreManager.lookup('KReportDesignerListFieldsStore'),
                                            valueField: 'fieldid',
                                            displayField: 'name',
                                            listeners: {
                                                select: function (thisCombo) {

                                                }
                                            }
                                        }
                                    ]
                                },
                                {
                                    xtype: 'panel',
                                    flex: 1,
                                    border: false,
                                    layout: 'fit',
                                    width: '100%',
                                    items: [
                                        {
                                            xtype: 'combo',
                                            itemId: 'chartseries',
                                            editable: false,
                                            border: 1,
                                            fieldLabel: languageGetText('LBL_CHARTTYPE_DATASERIES'),
                                            triggerAction: 'all',
                                            lazyRender: true,
                                            queryMode: 'local',
                                            store: Ext.data.StoreManager.lookup('KReportDesignerListFieldsStore'),
                                            valueField: 'fieldid',
                                            displayField: 'name',
                                            listeners: {
                                                select: function (thisCombo) {

                                                }
                                            }
                                        }
                                    ]
                                }

                            ]
                        }]
                }, {
                    xtype: 'fieldset',
                    defaultType: 'textfield',
                    title: languageGetText('LBL_CHARTOPTIONS_FS'),
                    collapsible: false,
                    layout: 'hbox',
                    defaults: {
                        padding: 10
                    },
                    items: [
                        {
                            xtype: 'panel',
                            border: false,
                            flex: 1,
                            layout: 'vbox',
                            defaults: {
                                width: '100%'
                            },
                            items: [
                                {
                                    flex: 1,
                                    xtype: 'textfield',
                                    itemId: 'optionsTitle',
                                    fieldLabel: languageGetText('LBL_CHARTOPTIONS_TITLE')
                                }, {
                                    xtype: 'textfield',
                                    itemId: 'chartOptionsContext',
                                    fieldLabel: languageGetText('LBL_CHARTOPTIONS_CONTEXT')
                                }]
                        },
                        {
                            xtype: 'panel',
                            border: false,
                            flex: 1,
                            layout: 'fit',
                            items: [{
                                columns: 1,
                                flex: 1,
                                // vertical: true,
                                xtype: 'checkboxgroup',
                                //  fieldLabel: languageGetText('LBL_CHARTOPTIONS_FS'),
                                itemId: 'optionsCheckBoxGroup',
                                items: [
                                    {
                                        boxLabel: languageGetText('LBL_CHARTOPTIONS_LEGEND'),
                                        name: 'legend',
                                        selected: true,
                                        inputValue: true
                                    }, {
                                        boxLabel: languageGetText('LBL_CHARTOPTIONS_3D'),
                                        name: 'is3D'
                                    }
                                ]
                            }
                            ]
                        }
                    ]
                }
            ]
        }
    ],
    setPanelData: function (_chartData) {
        this.chartData = _chartData;

        if (this.chartData.dims !== undefined) {
            this.down('combo[itemId=chartdimension]').setValue(this.chartData.dims);
            this.down('combo[itemId=charttype]').setValue(this.chartData.type);
            this.down('combo[itemId=dimension1]').setValue(this.chartData.dimensions.dimension1);

            switch (this.chartData.dims.substr(2, 1)) {
                case '1':
                    if (this.chartData.dataseries[0])
                        this.down('combo[itemId=chartseries]').setValue(this.chartData.dataseries[0].fieldid);
                    break;
            }


            this.down('textfield[itemId=optionsTitle]').setValue(this.chartData.title);
            this.down('textfield[itemId=chartOptionsContext]').setValue(this.chartData.context);
        }
        else {

            this.down('combo[itemId=charttype]').clearValue();
            this.down('combo[itemId=dimension1]').clearValue();
            this.down('combo[itemId=chartseries]').clearValue();
            this.down('textfield[itemId=optionsTitle]').setValue();
            this.down('combo[itemId=chartdimension]').setValue('111');
            this.down('textfield[itemId=chartOptionsContext]').setValue();
        }

        if (this.chartData.options !== undefined)
            this.down('checkboxgroup[itemId=optionsCheckBoxGroup]').setValue(this.chartData.options);
        else
            this.down('checkboxgroup[itemId=optionsCheckBoxGroup]').setValue();

        // set the Color schema
        if (this.chartData.colors !== undefined)
            this.down('combo[itemId=colorCombo]').setValue(this.chartData.colors);
        else
            this.down('combo[itemId=colorCombo]').setValue('default');
        // fire the select event
        this.down('combo[itemId=chartdimension]').fireEvent('select', this.down('combo[itemId=chartdimension]'));
        // set the color preview
        this.down('displayfield[itemId=colorDisplay]').updateColors();

    },
    getPanelData: function () {

        if (this.chartData.uid === undefined)
            this.chartData.uid = SpiceCRM.KReporter.Designer.Application.kGuid();
        this.chartData.dims = this.down('combo[itemId=chartdimension]').getValue();
        this.chartData.type = this.down('combo[itemId=charttype]').getValue();
        this.chartData.dimensions = {
            dimension1: this.down('combo[itemId=dimension1]').getValue()
        };

        // the dataseries
        this.chartData.dataseries = [];
        
        switch (this.chartData.dims.substr(2, 1)) {
            case '1':
                if (this.down('combo[itemId=chartseries]').getValue() !== null)
                    this.chartData.dataseries.push({
                        fieldid: this.down('combo[itemId=chartseries]').getValue(),
                        name: Ext.data.StoreManager.lookup('KReportDesignerListFieldsStore').findRecord('fieldid', this.down('combo[itemId=chartseries]').getValue()).get('name'),
                        chartfunction: 'SUM',
                        meaning: 'value',
                        axis: 'P',
                        renderer: 'bars'
                    });
                break;
        }

        // options
        this.chartData.options = this.down('checkboxgroup[itemId=optionsCheckBoxGroup]').getValue();
        this.chartData.title = this.down('textfield[itemId=optionsTitle]').getValue();
        this.chartData.context = this.down('textfield[itemId=chartOptionsContext]').getValue();
        this.chartData.colors = this.down('combo[itemId=colorCombo]').getValue();

        return this.chartData;
    }
});
