@extends('layout.header')
@section('content')
    <style>
        .div-rul a {
            margin-top: -40px;

        }

        button:disabled {
            color: #a0a0a0;
        }

    </style>

    <script src="https://unpkg.com/ag-grid-enterprise@16.0.0/dist/ag-grid-enterprise.min.js"></script>

    <div class="am-content">
        <div class="page-head">

            <h2> Equipment</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Equipment Management</a></li>
                <li><a href="#">Equipment Model</a></li>


            </ol>


            {{--<div class="text-right div-rul">--}}
                {{--<a href="{{url('admin/addview')}}" class="btn btn-space btn-primary">Create Equipment Model</a>--}}
                {{--<a href="{{url('admin/customerExport/'.$customerId)}}" class="btn btn-space btn-primary">Export</a>--}}
                {{--<a href="#"></a>--}}


            {{--</div>--}}
        </div>
        <div class="main-content">
            <div class="row styleforsearch">
                {{--<div class="panel panel-default keywordsearchpanel">--}}


                {{--<div class="panel-body">--}}
                {{--<legend>Keyword Search</legend>--}}

                {{--<form action="#" method="post">--}}
                {{--<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">--}}
                {{--<div class="col-md-4">--}}
                {{--<div class="form-group">--}}


                {{--</div>--}}

                {{--</div>--}}

                {{--</form>--}}
                {{--</div>--}}
                {{--</div>--}}
            </div>

            <div class="row">
                <div class="panel panel-default">

                    <div class="panel-body">

                        {{--<div id="myGrid" style="height: 176px;" class="ag-theme-fresh"></div>--}}
                        <div class="col-sm-18">
                            <div class="widget widget-fullwidth widget-small">

                                <div class="flash-message">
                                    @include('notification/notification')
                                </div>
                                <div class="row">
                                    <legend>Equipment Model Details <span id="rowCount"></span></legend>


                                    <div class='textInput'>
                                        <input type="text" id="quickFilterInput" name="keyword"
                                               placeholder="Type text to Filter..."
                                               class="form-control" value="{!! $keyword !!}"
                                               style="margin:  auto;margin-top: 6px;">

                                    </div>


                                </div>

                                <div style="width: 100%; height: 400px;"
                                     id="bestHtml5Grid"
                                     class="ag-theme-material">
                                </div>


                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div>
                <div style="padding: 4px">


                    <div style="clear: both;"></div>
                </div>

            </div>

        </div>
    </div>

    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/underscore/underscore.js')}}"></script>

    <style>
        .textInput {
            position: relative;
            bottom: 10px;
            right: 14px;
            width: 50%;
            float: right;
        }

        #quickFilterInput {
            border-radius: 5px;
            display: block;
            width: 100%;
            padding: .375rem .75rem;
            /* font-size: 1rem; */
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-image: none;
            background-clip: padding-box;
            border: 1px solid #ced4da;
        }

        .ag-header-container {
            background-color: #f0f0f1;
            color: black;
        }

        .ag-icon ag-icon-menu {
            background-color: white !important;
        }

        .ag-body {

            font-weight: bold;
        }
    </style>


    <script>
        var ROW_DATA = {!! $jsonvalue !!}

            function createRowData() {
            return ROW_DATA;
        }
        (function () {


            var columnDefs = [
                {
                    headerName: '', checkboxSelection: true, suppressSorting: true,
                    suppressMenu: true
                },
                {
                    headerName: 'Model Name', field: "name",

                },
                {
                    headerName: 'Product Type', field: "producttype",

                },
                {
                    headerName: 'Brand Name', field: "brand",

                },
                {
                    headerName: 'Brand Operation', field: "brandoperation",

                },
                {
                    headerName: 'Channel Name', field: "channel",

                },
                {
                    headerName: 'Number of Channels', field: "channelNo",

                },
                {
                    headerName: 'Volume', field: "volume",

                }


            ];

            var gridOptions = {
                columnDefs: columnDefs,
                rowSelection: 'multiple',
                enableColResize: true,
                enableSorting: true,
                enableFilter: true,
                enableRangeSelection: true,
                suppressRowClickSelection: true,
                animateRows: true,
                onModelUpdated: modelUpdated,
                debug: true
            };

            var btBringGridBack;
            var btDestroyGrid;

            // wait for the document to be loaded, otherwise
            // ag-Grid will not find the div in the document.
            document.addEventListener("DOMContentLoaded", function () {
                btBringGridBack = document.querySelector('#btBringGridBack');
                btDestroyGrid = document.querySelector('#btDestroyGrid');

                // this example is also used in the website landing page, where
                // we don't display the buttons, so we check for the buttons existance
                if (btBringGridBack) {
                    btBringGridBack.addEventListener('click', onBtBringGridBack);
                    btDestroyGrid.addEventListener('click', onBtDestroyGrid);
                }

                addQuickFilterListener();
                onBtBringGridBack();
            });

            function onBtBringGridBack() {
                var eGridDiv = document.querySelector('#bestHtml5Grid');
                new agGrid.Grid(eGridDiv, gridOptions);
                if (btBringGridBack) {
                    btBringGridBack.disabled = true;
                    btDestroyGrid.disabled = false;
                }
                // createRowData is available in data.js
                gridOptions.api.setRowData(createRowData());
            }

            function onBtDestroyGrid() {
                btBringGridBack.disabled = false;
                btDestroyGrid.disabled = true;
                gridOptions.api.destroy();
            }

            function addQuickFilterListener() {
                var eInput = document.querySelector('#quickFilterInput');
                eInput.addEventListener("input", function () {
                    var text = eInput.value;
                    gridOptions.api.setQuickFilter(text);
                });
            }

            function modelUpdated() {
                var model = gridOptions.api.getModel();
                var totalRows = model.getTopLevelNodes().length;
                var processedRows = model.getRowCount();
                var eSpan = document.querySelector('#rowCount');
                eSpan.innerHTML = processedRows.toLocaleString() + ' / ' + totalRows.toLocaleString();
            }


            var SKILL_TEMPLATE =
                '<label style="border: 1px solid lightgrey; margin: 4px; padding: 4px; display: inline-block;">' +
                '  <span>' +
                '    <div style="text-align: center;">SKILL_NAME</div>' +
                '    <div>' +
                '      <input type="checkbox"/>' +
                '      <img src="https://www.ag-grid.com/images/skills/SKILL.png" width="30px"/>' +
                '    </div>' +
                '  </span>' +
                '</label>';

            var FILTER_TITLE =
                '<div style="text-align: center; background: lightgray; width: 100%; display: block; border-bottom: 1px solid grey;">' +
                '<b>TITLE_NAME</b>' +
                '</div>';

            function SkillFilter() {
            }

            SkillFilter.prototype.init = function (params) {
                this.filterChangedCallback = params.filterChangedCallback;
                this.model = {
                    android: false,
                    css: false,
                    html5: false,
                    mac: false,
                    windows: false
                };
            };

            SkillFilter.prototype.getModel = function () {

            };

            SkillFilter.prototype.setModel = function (model) {

            };

            SkillFilter.prototype.getGui = function () {
                var eGui = document.createElement('div');
                var eInstructions = document.createElement('div');
                eInstructions.innerHTML = FILTER_TITLE.replace('TITLE_NAME', 'Custom Skills Filter');
                eGui.appendChild(eInstructions);

                var that = this;


                return eGui;
            };

            SkillFilter.prototype.doesFilterPass = function (params) {

                var rowSkills = params.data.skills;
                var model = this.model;
                var passed = true;

                IT_SKILLS.forEach(function (skill) {
                    if (model[skill]) {
                        if (!rowSkills[skill]) {
                            passed = false;
                        }
                    }
                });

                return passed;
            };

            SkillFilter.prototype.isFilterActive = function () {
                var model = this.model;
                var somethingSelected = model.android || model.css || model.html5 || model.mac || model.windows;
                return somethingSelected;
            };

            var PROFICIENCY_TEMPLATE =
                '<label style="padding-left: 4px;">' +
                '<input type="radio" name="RANDOM"/>' +
                'PROFICIENCY_NAME' +
                '</label>';

            var PROFICIENCY_NONE = 'none';
            var PROFICIENCY_ABOVE40 = 'above40';
            var PROFICIENCY_ABOVE60 = 'above60';
            var PROFICIENCY_ABOVE80 = 'above80';

            var PROFICIENCY_NAMES = ['No Filter', 'Above 40%', 'Above 60%', 'Above 80%'];
            var PROFICIENCY_VALUES = [PROFICIENCY_NONE, PROFICIENCY_ABOVE40, PROFICIENCY_ABOVE60, PROFICIENCY_ABOVE80];

            function ProficiencyFilter() {
            }

            ProficiencyFilter.prototype.init = function (params) {
                this.filterChangedCallback = params.filterChangedCallback;
                this.selected = PROFICIENCY_NONE;
                this.valueGetter = params.valueGetter;
            };

            ProficiencyFilter.prototype.getModel = function () {

            };

            ProficiencyFilter.prototype.setModel = function (model) {

            };

            ProficiencyFilter.prototype.getGui = function () {
                var eGui = document.createElement('div');
                var eInstructions = document.createElement('div');
                eInstructions.innerHTML = FILTER_TITLE.replace('TITLE_NAME', 'Custom Proficiency Filter');
                eGui.appendChild(eInstructions);

                var random = '' + Math.random();

                var that = this;
                PROFICIENCY_NAMES.forEach(function (name, index) {
                    var eFilter = document.createElement('div');
                    var html = PROFICIENCY_TEMPLATE.replace('PROFICIENCY_NAME', name).replace('RANDOM', random);
                    eFilter.innerHTML = html;
                    var eRadio = eFilter.querySelector('input');
                    if (index === 0) {
                        eRadio.checked = true;
                    }
                    eGui.appendChild(eFilter);

                    eRadio.addEventListener('click', function () {
                        that.selected = PROFICIENCY_VALUES[index];
                        that.filterChangedCallback();
                    });
                });

                return eGui;
            };

            ProficiencyFilter.prototype.doesFilterPass = function (params) {

                var value = this.valueGetter(params);
                var valueAsNumber = parseFloat(value);

                switch (this.selected) {
                    case PROFICIENCY_ABOVE40 :
                        return valueAsNumber >= 40;
                    case PROFICIENCY_ABOVE60 :
                        return valueAsNumber >= 60;
                    case PROFICIENCY_ABOVE80 :
                        return valueAsNumber >= 80;
                    default :
                        return true;
                }

            };

            ProficiencyFilter.prototype.isFilterActive = function () {
                return this.selected !== PROFICIENCY_NONE;
            };
        })();
    </script>


@stop

