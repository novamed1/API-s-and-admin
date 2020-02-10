@extends('layout.header')
@section('content')

<div class="am-content">
      <div class="main-content">
        <div class="row">
          <div class="col-md-7">
            <div class="widget widget-fullwidth line-chart">
              <div class="widget-head">
                <div class="tools"></div><span class="title">Download Stats</span>
              </div>
              <div class="chart-container">
                <div class="counter">
                  <div class="value">19.5%</div>
                  <div class="desc">New Downloads</div>
                </div>
                <div id="line-chart-live" style="height: 285px;"></div>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="row">
              <div class="col-md-6">
                        <div class="widget widget-tile">
                          <div class="data-info">
                            <div data-toggle="counter" data-end="96" class="value">0%</div>
                            <div class="desc">Requests received</div>
                          </div>
                          <div class="icon"><span class="s7-bookmarks"></span></div>
                        </div>
              </div>
              <div class="col-md-6">
                        <div class="widget widget-tile">
                          <div class="data-info">
                            <div data-toggle="counter" data-end="233" class="value">0%</div>
                            <div class="desc">Instruments</div>
                          </div>
                          <div class="icon"><span class="s7-compass"></span></div>
                        </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                        <div class="widget widget-tile">
                          <div class="data-info">
                            <div data-toggle="counter" data-end="156" class="value">0</div>
                            <div class="desc">Job assigned</div>
                          </div>
                          <div class="icon"><span class="s7-airplay"></span></div>
                        </div>
              </div>
              <div class="col-md-6">
                        <div class="widget widget-tile">
                          <div class="data-info">
                            <div data-toggle="counter" data-end="85" class="value">0</div>
                            <div class="desc">Workorders</div>
                          </div>
                          <div class="icon"><span class="s7-news-paper"></span></div>
                        </div>
              </div>
            </div>

          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="widget widget-fullwidth line-chart">
              <div class="widget-head">
                <div class="tools"><span class="icon s7-upload"></span><span class="icon s7-edit"></span><span class="icon s7-close"></span></div><span class="title">Site Visits</span>
              </div>
              <div class="chart-container">
                <div class="counter">
                  <div class="value">80%</div>
                  <div class="desc">More Visits</div>
                </div>
                <div id="line-chart1" style="height: 290px;"></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="widget">
              <div class="widget-head">
                <div class="tools"></div><span class="title">Overall</span>
              </div>
              <div class="chart-container">
                <div id="pie-chart4" style="height: 260px;"></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="widget widget-fullwidth">
              <div class="widget-head">
                <div class="tools"></div><span class="title">Requests</span>
              </div>
              <div class="chart-container">

                <div id="bar-chart1" style="height: 290px;"></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
@stop