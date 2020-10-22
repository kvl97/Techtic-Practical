@extends('frontend.layouts.default')
@section('content')

    <div class="main-content">
        <section class="rocket-info">
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <div class="rocket-info-left dropdown-rocket">
                            <div class="dropdown-pills">
                                <a href="javascript:void(0);" class="dropbtn-rocket btn-filter icon icon-down-arrow">Select</a>
                            </div>
                            <div class="nav flex-column nav-pills dropdown-content-rocket" id="tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link" id="v-location-tab" href="{{SITE_URL}}location-information" role="tab" aria-controls="location" aria-selected="true">Location Information</a>
                                <a class="nav-link" id="runs-tab" href="{{SITE_URL}}display-line-runs" role="tab" aria-controls="runs" aria-selected="false">Display Line Runs</a>
                                <a class="nav-link" id="passenger-information-tab" href="{{SITE_URL}}passenger-information" role="tab" aria-controls="passenger-information" aria-selected="false">Passenger Information</a>
                                <a class="nav-link active" id="luggage-animals-tab" href="{{SITE_URL}}luggage-animals" role="tab" aria-controls="luggage-animals" aria-selected="false">Luggage and Animals</a>
                                <a class="nav-link" id="travel-tab" href="{{SITE_URL}}travel-details" role="tab" aria-controls="travel" aria-selected="false">Travel Details</a>
                                <a class="nav-link" id="confirm-tab" href="{{SITE_URL}}currently-assigned-line-runs" role="tab" aria-controls="confirm" aria-selected="false">Currently Assigned Line Run</a>
                                <a class="nav-link" id="reservation-summary-tab" href="{{SITE_URL}}reservation-summary" role="tab" aria-controls="reservation-summary" aria-selected="false">Reservation Summary</a>
                                <a class="nav-link" id="payment-tab" href="{{SITE_URL}}payment" role="tab" aria-controls="payment" aria-selected="false">Payment</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="rocket-info-right">
                            <div class="tab-content" id="v-tabContent">
                                <!-- 4 -->
                                <div class="tab-pane fade show active" id="luggage-animals" role="tabpanel" aria-labelledby="luggage-animals-tab">
                                    <div class="rocket-info-details rocket-info-luggage-animals quote-detail">
                                        <h3 class="rocket-info__title"> Luggage and Animals </h3>
                                        <div class="rocket-info__four">
                                            <h4 class="rocket-info__title pb-2"> 1st Date of Travel </h4>
                                            <div class="form-wrapper">
                                                <form action="">
                                                    <div class="row form-bottom">
                                                        <div class="col-sm-12">
                                                            <div class="counter-from">
                                                                <div class="counter-block counter-from-bg row m-0">
                                                                    <div class="counter-title d-flex">
                                                                        <p class="m-0 mr-2">Number of Travelers</p>
                                                                        <input class="form-control" type="text" placeholder="5">
                                                                    </div>
                                                                    <div class="btn-block d-md-flex d-none align-items-center justify-content-center ">
                                                                        <p>CHARGE</p>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Checked Luggage <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-17">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block d-flex align-items-center justify-content-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Oversized Luggage <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-18">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Overweight Luggagery <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-19">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Carry On Luggage <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-20">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Personal Items <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-21">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Cardboard boxes <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-22">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Plastic Totes <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-23">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Fish Box <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-24">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Golf Clubs <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-25">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Surfboards <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-26">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Bicycle in box <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-27">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Folding Wheelchair <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-28">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Folding Walker <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-29">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Fishing Poies <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-30">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Skis <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span>
                                                                        </p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-31">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Snowboard <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-32">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Car Seat <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-33">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>

                                                                <div class="counter-block counter-from-bg row m-0">
                                                                    <div class="counter-title d-flex">
                                                                        <p class="m-0 mr-2">Number of Pets</p>
                                                                        <input class="form-control" type="text" placeholder="5">
                                                                    </div>
                                                                    <div class="btn-block d-md-flex d-none align-items-center justify-content-center">
                                                                        <p>CHARGE</p>
                                                                    </div>
                                                                </div>

                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Small carrier 21" x 16" x15" <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="custom-checkbox mr-3 mr-lg-0">
                                                                            <input type="checkbox" id="checkbox1">
                                                                            <label for="checkbox1">&nbsp;</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block d-flex align-items-center justify-content-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Medium carrier 27" x 21 ½" x 20" ge <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span>
                                                                        </p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="custom-checkbox mr-3 mr-lg-0">
                                                                            <input type="checkbox" id="checkbox2">
                                                                            <label for="checkbox2">&nbsp;</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block d-flex align-items-center justify-content-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Large carrier 36" x 24 ½" x 26" agery <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span>
                                                                        </p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="custom-checkbox mr-3 mr-lg-0">
                                                                            <input type="checkbox" id="checkbox3">
                                                                            <label for="checkbox3">&nbsp;</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block d-flex align-items-center justify-content-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>X-Large carrier 40" x 27" x 30" e <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span>
                                                                        </p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="custom-checkbox mr-3 mr-lg-0">
                                                                            <input type="checkbox" id="checkbox4">
                                                                            <label for="checkbox4">&nbsp;</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block d-flex align-items-center justify-content-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="rocket-info__four">
                                            <div class="d-flex align-items-center justify-content-between flex-sm-nowrap flex-wrap">
                                                <h4 class="rocket-info__title pb-2">2nd Date of Travel</h4>
                                                <div class="custom-control custom-switch mb-10">
                                                    <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                                    <label class="custom-control-label" for="customSwitch1">Same as above</label>
                                                </div>
                                            </div>
                                            <div class="form-wrapper">
                                                <form action="">
                                                    <div class="row form-bottom">
                                                        <div class="col-sm-12">
                                                            <div class="counter-from">
                                                                <div class="counter-block counter-from-bg row m-0">
                                                                    <div class="counter-title d-flex">
                                                                        <p class="m-0 mr-2">Number of Travelers</p>
                                                                        <input class="form-control" type="text" placeholder="5">
                                                                    </div>
                                                                    <div class="btn-block d-md-flex d-none align-items-center justify-content-center">
                                                                        <p>CHARGE</p>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Checked Luggage <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-17">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block d-flex align-items-center justify-content-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Oversized Luggage <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-18">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Overweight Luggagery <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-19">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Carry On Luggage <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-20">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Personal Items <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-21">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Cardboard boxes <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-22">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Plastic Totes <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-23">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Fish Box <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-24">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Golf Clubs <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-25">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Surfboards <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-26">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Bicycle in box <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-27">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Folding Wheelchair <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-28">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Folding Walker <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-29">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Fishing Poies <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-30">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Skis <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span>
                                                                        </p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-31">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Snowboard <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-32">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Car Seat <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="select-field">
                                                                            <select id="field-33">
                                                                            <option value="Near by">1 to 20</option>
                                                                            <option value="1">1 to 20</option>
                                                                            <option value="2">2 to 20</option>
                                                                            <option value="3">3 to 20</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block justify-content-center d-flex align-items-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>

                                                                <div class="counter-block counter-from-bg row m-0">
                                                                    <div class="counter-title d-flex">
                                                                        <p class="m-0 mr-2">Number of Pets</p>
                                                                        <input class="form-control" type="text" placeholder="5">
                                                                    </div>
                                                                    <div class="btn-block d-md-flex d-none align-items-center justify-content-center">
                                                                        <p>CHARGE</p>
                                                                    </div>
                                                                </div>

                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Small carrier 21" x 16" x15" <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="custom-checkbox mr-3 mr-lg-0">
                                                                            <input type="checkbox" id="checkbox-a">
                                                                            <label for="checkbox-a">&nbsp;</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block d-flex align-items-center justify-content-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Medium carrier 27" x 21 ½" x 20" ge <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span>
                                                                        </p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="custom-checkbox mr-3 mr-lg-0">
                                                                            <input type="checkbox" id="checkbox-b">
                                                                            <label for="checkbox-b">&nbsp;</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block d-flex align-items-center justify-content-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>Large carrier 36" x 24 ½" x 26" agery <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span>
                                                                        </p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="custom-checkbox mr-3 mr-lg-0">
                                                                            <input type="checkbox" id="checkbox-c">
                                                                            <label for="checkbox-c">&nbsp;</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block d-flex align-items-center justify-content-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                                <div class="counter-block row m-0">
                                                                    <div class="counter-title">
                                                                        <p>X-Large carrier 40" x 27" x 30" e <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span>
                                                                        </p>
                                                                    </div>
                                                                    <div class="counter-dropdown">
                                                                        <div class="custom-checkbox mr-3 mr-lg-0">
                                                                            <input type="checkbox" id="checkbox-d">
                                                                            <label for="checkbox-d">&nbsp;</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-block d-flex align-items-center justify-content-center">
                                                                        <a href="#" class="btn btn-sm btn-yellow">$5 each</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rocket-info__next mt-4 text-right">
                                        <a href="javascript:void(0);" class="btn btn-md btnNext btn-purple">Next</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@section('custom_js')
<script>
    $(document).ready(function() {
        KTFrontend.init();
    });
</script>
@stop

@stop
        