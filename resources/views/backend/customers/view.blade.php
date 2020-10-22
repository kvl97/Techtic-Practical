<style>
    @media print{
        #printPage, #back-to-list, #kt_aside{
            display: none;
        }
    }
</style>
@extends('backend.layouts.default')
@section('content')

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    </div>
    <!-- end:: Subheader -->
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <!--Begin::Dashboard 1-->
        <!--Begin::Row-->

        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Traveler Information:
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ ADMIN_URL }}customers/edit/{{ $customer_id }}#kt_tabs_reservation" class="btn  btn-secondary btn-icon-sm " id="back-to-list">
                                Back To Listing
                            </a>
                            <button class="btn btn-secondary btn-icon-sm" id="printPage">Print</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body contentToPrint" id="contentToPrint">
                <script>
                   
                </script>
                <div class="row" style="display: flex;flex-wrap: wrap;">
                    <div class="col-md-6 col-lg-6 col-sm-12" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                        <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                            <div class="col-md-6 col-lg-6 col-sm-12" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                <label class="heading-label" style="font-size: 16px;font-weight: 700;margin-bottom: 10px;line-height: 24px;display: block;font-family: 'Roboto', sans-serif;">Main traveler</label>
                                <p style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;font-family: 'Roboto', sans-serif;"> {{ $record['customers']['v_firstname'].' '.$record['customers']['v_lastname'] }}</p>
                                <p style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;font-family: 'Roboto', sans-serif;">{{ date(DATE_FORMAT,strtotime($record['created_at'])) }}</p>
                                <p style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;font-family: 'Roboto', sans-serif;"> {{ $record['v_contact_email_add'] }} </p>
                                <p style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;font-family: 'Roboto', sans-serif;"> {{ $record['v_contact_phone_number'] }} </p>
                                <br>
                                <div class="row no-gutters">
                                    <label class="col-form-label" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: inline-block;font-family: 'Roboto', sans-serif;font-weight: 700;">Booked Trip: </label>
                                    <label class="kt-mt-10 pl-2" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: inline-block;font-family: 'Roboto', sans-serif;">
                                        <?php if($record['e_class_type'] == 'RT') { echo "Round Trip"; } else { echo "One Way"; } ?></label>
                                </div>

                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                    <div class="form-group kt-mb-0 col-12" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                        <div class="row no-gutters">
                                            <label class="col-form-label" style="font-size: 13px;line-height: 15px;display: inline-block;font-family: 'Roboto', sans-serif;font-weight: 700;">Total Travelers:</label>
                                            <label class="kt-mt-10 pl-2" style="font-size: 13px;line-height: 15px;display: inline-block;font-family: 'Roboto', sans-serif;"> {{ $record['i_total_num_passengers'] }} </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                            @if($record['e_class_type'] == 'OW')
                                <div class="traveller-location-information_ow col-12" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                    <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                        <div class="col-12 heading-label" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                            <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                <div class="col-md-6" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                    <p style="font-size: 16px;font-weight: 700;margin-bottom: 16px;margin-top: 0;font-family: 'Roboto', sans-serif;">Leg of Travel:</p>
                                                </div>
                                                <div class="col-md-6" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                    <label style="font-size: 16px;font-weight: 700;margin-bottom: 16px;font-family: 'Roboto', sans-serif;">
                                                        {{ date(DATE_FORMAT,strtotime($record['d_depart_date'])) }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">{{ $record['geo_origin_service_area']['v_city'].' ('.$record['geo_origin_service_area']['v_country'].') to '.$record['geo_dest_service_area']['v_city'].' ('.$record['geo_dest_service_area']['v_country'].')' }}</label>
                                        <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                            <div class="col-xl-10 col-12 ml-auto" style="flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                    <div class="col-xl-5 col-md-6" style="flex: 0 0 41.6666666667%;max-width: 41.6666666667%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                        <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"> {{ 'To arrive at '.$record['geo_dest_service_area']['v_city'].' between'}}</label>
                                                    </div>
                                                    <div class="col-xl-6 col-md-6 pl-0" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;box-sizing: border-box;">
                                                        <p style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">14:00 (2:00pm) and 16:15 (4:15pm)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                        <div class="col-md-6 col-lg-6 col-sm-12" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                            <label> 
                                                <strong style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">Pickup location :
                                                    {{ $record['geo_origin_service_area']['v_city'].' ('.$record['geo_origin_service_area']['v_country'].')' }}
                                                </strong>
                                            </label>
                                            <div class="">
                                                <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Address :</strong> {{ $record['geo_origin_service_area']['v_street1'] }}</label>
                                                <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>City :</strong> {{ $record['geo_origin_service_area']['v_city'] }}</label>
                                                <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Zipcode :</strong> {{ $record['geo_origin_service_area']['v_postal_code'] }}</label>
                                                <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Cross Street :</strong> {{ $record['geo_origin_service_area']['v_country'] }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-12" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                            <label> 
                                                <strong style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">Drop-off location :
                                                    {{ $record['geo_dest_service_area']['v_city'].' ('.$record['geo_dest_service_area']['v_country'].')' }}
                                                </strong>
                                            </label>
                                            <div class="">
                                                <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Address :</strong> {{ $record['geo_dest_service_area']['v_street1'] }}</label>
                                                <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>City :</strong> {{ $record['geo_dest_service_area']['v_city'] }}</label>
                                                <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Zipcode :</strong> {{ $record['geo_dest_service_area']['v_postal_code'] }}</label>
                                                <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Cross Street :</strong> {{ $record['geo_dest_service_area']['v_country'] }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="traveller-location-information_rt col-12" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                    <div class="kt-mt-30" style="margin-top: 30px">
                                        <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                            <div class="col-12 heading-label" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                    <div class="col-md-6" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                        <p style="font-size: 16px;font-weight: 700;margin-bottom: 16px;margin-top: 0;font-family: 'Roboto', sans-serif;">1<sup>st</sup>Leg of Travel:</p>
                                                    </div>
                                                    <div class="col-md-6" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                        <label style="font-size: 16px;font-weight: 700;margin-bottom: 16px;font-family: 'Roboto', sans-serif;">
                                                            {{ date(DATE_FORMAT,strtotime($record['d_depart_date'])) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">{{ $record['geo_origin_service_area']['v_city'].' ('.$record['geo_origin_service_area']['v_country'].') to '.$record['geo_dest_service_area']['v_city'].' ('.$record['geo_dest_service_area']['v_country'].')' }}</label>
                                            <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                            <div class="col-xl-10 col-12 ml-auto" style="flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                    <div class="col-xl-5 col-md-6" style="flex: 0 0 41.6666666667%;max-width: 41.6666666667%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                        <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">{{ 'To arrive at '.$record['geo_dest_service_area']['v_city'].' between'}}</label>
                                                    </div>
                                                    <div class="col-xl-6 col-md-6 pl-0" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;box-sizing: border-box;">
                                                        <p style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">14:00 (2:00pm) and 16:15 (4:15pm)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                            <div class="col-md-6 col-lg-6 col-sm-12" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                <label> 
                                                    <strong style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">Pickup location :
                                                        {{ $record['geo_origin_service_area']['v_city'].' ('.$record['geo_origin_service_area']['v_country'].')' }}
                                                    </strong>
                                                </label>
                                                <div class="">
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Address :</strong> {{ $record['geo_origin_service_area']['v_street1'] }}</label>
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>City :</strong> {{ $record['geo_origin_service_area']['v_city'] }}</label>
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Zipcode :</strong> {{ $record['geo_origin_service_area']['v_postal_code'] }}</label>
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Cross Street :</strong> {{ $record['geo_origin_service_area']['v_country'] }}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6 col-sm-12" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                <label> 
                                                    <strong style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">Drop-off location :
                                                        {{ $record['geo_dest_service_area']['v_city'].' ('.$record['geo_dest_service_area']['v_country'].')' }}
                                                    </strong>
                                                </label>
                                                <div class="">
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Address :</strong> {{ $record['geo_dest_service_area']['v_street1'] }}</label>
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>City :</strong> {{ $record['geo_dest_service_area']['v_city'] }}</label>
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Zipcode :</strong> {{ $record['geo_dest_service_area']['v_postal_code'] }}</label>
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Cross Street :</strong> {{ $record['geo_dest_service_area']['v_country'] }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="kt-mt-30" style="margin-top: 30px">
                                        <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                            <div class="col-12 heading-label" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                    <div class="col-md-6" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                        <p style="font-size: 16px;font-weight: 700;margin-bottom: 16px;margin-top: 0;font-family: 'Roboto', sans-serif;">2<sup>nd</sup>Leg of Travel:</p>
                                                    </div>
                                                    <div class="col-md-6" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                        <label style="font-size: 16px;font-weight: 700;margin-bottom: 16px;font-family: 'Roboto', sans-serif;">
                                                            {{ date(DATE_FORMAT,strtotime($record['d_depart_date'])) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">{{ $record['geo_dest_service_area']['v_city'].' ('.$record['geo_dest_service_area']['v_country'].') to '.$record['geo_origin_service_area']['v_city'].' ('.$record['geo_origin_service_area']['v_country'].')' }}</label>
                                            <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                            <div class="col-xl-10 col-12 ml-auto" style="flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                    <div class="col-xl-5 col-md-6"style="flex: 0 0 41.6666666667%;max-width: 41.6666666667%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                        <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">{{ 'To arrive at '.$record['geo_origin_service_area']['v_city'].' between'}}</label>
                                                    </div>
                                                    <div class="col-xl-6 col-md-6 pl-0" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;box-sizing: border-box;">
                                                        <p style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">14:00 (2:00pm) and 16:15 (4:15pm)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                            <div class="col-md-6 col-lg-6 col-sm-12" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                <label> 
                                                    <strong style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">Pickup location :
                                                        {{ $record['geo_dest_service_area']['v_city'].' ('.$record['geo_dest_service_area']['v_country'].')' }}
                                                    </strong>
                                                </label>
                                                <div class="">
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Address :</strong> {{ $record['geo_dest_service_area']['v_street1'] }}</label>
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>City :</strong> {{ $record['geo_dest_service_area']['v_city'] }}</label>
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Zipcode :</strong> {{ $record['geo_dest_service_area']['v_postal_code'] }}</label>
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Cross Street :</strong> {{ $record['geo_dest_service_area']['v_country'] }}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6 col-sm-12" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                <label> 
                                                    <strong style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">Drop-off location :
                                                        {{ $record['geo_origin_service_area']['v_city'].' ('.$record['geo_origin_service_area']['v_country'].')' }}
                                                    </strong>
                                                </label>
                                                <div class="">
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Address :</strong> {{ $record['geo_origin_service_area']['v_street1'] }}</label>
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>City :</strong> {{ $record['geo_origin_service_area']['v_city'] }}</label>
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Zipcode :</strong> {{ $record['geo_origin_service_area']['v_postal_code'] }}</label>
                                                    <label style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;"><strong>Cross Street :</strong> {{ $record['geo_origin_service_area']['v_country'] }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 col-sm-12" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                        <label class="heading-label" style="font-size: 16px;font-weight: 700;margin-bottom: 10px;line-height: 24px;display: block;font-family: 'Roboto', sans-serif;">Fare and Payment info</label>
                        <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                            <div class="col-12" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                <div class="row" style="display: flex;flex-wrap: wrap;">
                                    <label class="col-form-label" style="padding: 9.5px 10px;box-sizing: border-box;font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;font-weight: 700;">Mode of Payment: </label>
                                    <label class="kt-mt-10" style="font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;margin-top: 10px;"> {{ 'test record'}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                            <div class="col-12" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                <div class="row" style="display: flex;flex-wrap: wrap;">
                                    <label class="col-form-label" style="padding: 9.5px 10px;box-sizing: border-box;font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;font-weight: 700;">Payment Status: </label>
                                    <label class="kt-mt-10" style="font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;margin-top: 10px;"> {{ 'test record'}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                            <div class="col-12" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                <div class="row" style="display: flex;flex-wrap: wrap;">
                                    <label class="col-form-label" style="padding: 9.5px 10px;box-sizing: border-box;font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;font-weight: 700;">Total Fare Amount: </label>
                                    <label class="kt-mt-10" style="font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;margin-top: 10px;"> {{ $record['d_total_fare']}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3" style="margin-top: 13px;">
                            <label class="heading-label" style="font-size: 16px;font-weight: 700;margin-bottom: 10px;line-height: 24px;display: block;font-family: 'Roboto', sans-serif;">Breakdown of Charges:</label>
                            <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                <div class="col-xl-11 col-12 ml-auto" style="flex: 0 0 91.6666666667%;max-width: 91.6666666667%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                    <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                        <div class="col-md-6 col-lg-6 col-sm-12" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                            <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                <div class="col-md-12 col-12 ml-auto" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                    <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                        <label class="col-form-label col-md-5 col-lg-4 col-sm-12" style="flex: 0 0 33.3333333333%;max-width: 33.3333333333%;padding: 9.5px 10px;box-sizing: border-box;font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;font-weight: 700;">Fares: </label>
                                                        <div class="col-md-7 col-lg-7 col-sm-12 pl-4" style="flex: 0 0 58.3333333333%;max-width: 58.3333333333%;padding: 9.5px 10px;box-sizing: border-box;">
                                                            <label style="font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;margin-bottom: 0;"> {{ $total_fare_amount }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                <div class="col-md-12 col-12 ml-auto" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                    <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                        <label class="col-form-label col-md-5 col-lg-4 col-sm-12" style="flex: 0 0 33.3333333333%;max-width: 33.3333333333%;padding: 9.5px 10px;box-sizing: border-box;font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;font-weight: 700;">Other Charges:
                                                        </label>
                                                        <div class="col-md-7 col-lg-7 col-sm-12 pl-4" style="flex: 0 0 58.3333333333%;max-width: 58.3333333333%;padding: 9.5px 10px;box-sizing: border-box;">
                                                            <label style="font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;margin-bottom: 0;">  {{ $total_luggage_amount }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-12" style="flex: 0 0 50%;max-width: 50%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                            <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                <div class="col-md-12 col-12 ml-auto" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                    <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                        <label class="col-form-label col-md-5 col-lg-5 col-sm-12" style="flex: 0 0 33.3333333333%;max-width: 33.3333333333%;padding: 9.5px 10px;box-sizing: border-box;font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;font-weight: 700;">Mode of Payment:
                                                        </label>
                                                        <div class="col-md-7 col-lg-7 col-sm-12" style="flex: 0 0 58.3333333333%;max-width: 58.3333333333%;padding: 9.5px 10px;box-sizing: border-box;">
                                                            <label style="font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;margin-bottom: 0;"> {{ 'test record'}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                <div class="col-md-12 col-12 ml-auto" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                                    <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                        <label class="col-form-label col-md-5 col-lg-5 col-sm-12" style="flex: 0 0 33.3333333333%;max-width: 33.3333333333%;padding: 9.5px 10px;box-sizing: border-box;font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;font-weight: 700;">Payment Status:
                                                        </label>
                                                        <div class="col-md-7 col-lg-7 col-sm-12" style="flex: 0 0 58.3333333333%;max-width: 58.3333333333%;padding: 9.5px 10px;box-sizing: border-box;">
                                                            <label style="font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;margin-bottom: 0;"> {{ 'test record'}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-11 col-12 ml-auto" style="flex: 0 0 91.6666666667%;max-width: 91.6666666667%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                    <label class="col-form-label cst-width" style="width: 17.8%;min-width: 60px;text-align: right;font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;display:inline-block;font-weight: 700;">Total Fare:</label>
                                    <label class="kt-mt-10 pl-1" style="font-size: 13px;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;display:inline-block;"> {{ $total_fare_amount + $total_luggage_amount }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3" style="margin-top: 13px;">
                            <div>
                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                    <div class="col-xl-10 col-12 ml-auto fare-wrap" style="display:flex;flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                        <label class="kt-mr-30  heading-label"  style="font-size: 16px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 65%;font-weight: 700;">Fare Details:</label>
                                        @if($record['e_class_type'] == 'RT')
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;font-weight: 700;">1st Leg:</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;font-weight: 700;">2nd Leg:</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;font-weight: 700;">Total:</label>
                                        @else 
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;font-weight: 700;">Total:</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                    <div class="col-xl-10 col-12 ml-auto fare-wrap" style="display:flex;flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                        <label class="kt-mr-30 kt-mt-10" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 65%;">Adult Fare: ({{ $adult_cnt_rt + $adult_cnt }})</label>
                                        @if($record['e_class_type'] == 'RT')
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_adult }}</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_adult_rt }}</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_adult + $passanger_amount_adult_rt }}</label>
                                        @else
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_adult }}</label>            
                                        @endif
                                    </div>
                                </div>

                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                    <div class="col-xl-10 col-12 ml-auto fare-wrap" style="display:flex;flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                        <label class="kt-mr-30 kt-mt-10" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 65%;">Companion Details: ({{ $companion_cnt_rt + $companion_cnt }})</label>
                                        @if($record['e_class_type'] == 'RT')
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Companion }}</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Companion_rt }}</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Companion + $passanger_amount_Companion_rt }}</label>
                                        @else
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Companion }}</label>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                    <div class="col-xl-10 col-12 ml-auto fare-wrap" style="display:flex;flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                        <label class="kt-mr-30 kt-mt-10" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 65%;">Child Fare: ({{ $child_cnt_rt + $child_cnt }})</label>
                                        @if($record['e_class_type'] == 'RT')
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Child }}</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Child_rt }}</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Child + $passanger_amount_Child_rt }}</label>
                                        @else
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Child }}</label>
                                        @endif
                                        
                                    </div>
                                </div>

                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">      
                                    <div class="col-xl-10 col-12 ml-auto fare-wrap" style="display:flex;flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                        <label class="kt-mr-30 kt-mt-10" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 65%;">Infant Details: ({{ $infant_cnt_rt + $infant_cnt }})</label>
                                        @if($record['e_class_type'] == 'RT')
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Infant }}</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Infant_rt }}</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Infant + $passanger_amount_Infant_rt }}</label>
                                        @else
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Infant }}</label>
                                        @endif
                                        
                                    </div>
                                </div>

                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">     
                                    <div class="col-xl-10 col-12 ml-auto fare-wrap" style="display:flex;flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                        <label class="kt-mr-30 kt-mt-10" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 65%;">Military Fare: ({{ $military_cnt_rt + $military_cnt }})</label>
                                        @if($record['e_class_type'] == 'RT')
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Military }}</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Military_rt }}</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Military + $passanger_amount_Military_rt }}</label>
                                        @else
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Military }}</label>
                                        @endif
                                        
                                    </div>
                                </div>

                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                    <div class="col-xl-10 col-12 ml-auto fare-wrap" style="display:flex;flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                        <label class="kt-mr-30 kt-mt-10" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 65%;">Senior Fare: ({{ $senior_cnt_rt + $senior_cnt_rt }})</label>
                                        @if($record['e_class_type'] == 'RT')
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Senior }}</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Senior_rt }}</label>
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Senior + $passanger_amount_Senior_rt }}</label>
                                        @else
                                            <label class="kt-mr-30" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width:10%;margin-left:1.5%;">{{ $passanger_amount_Senior }}</label>
                                        @endif
                                        
                                    </div>
                                </div>

                                <div class="mt-3" style="margin-top: 13px;">
                                    <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                        <div class="col-xl-10 col-12 ml-auto luggage-wrap" style="display:flex;flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                            <label class="heading-label" style="font-size: 16px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;font-weight: 700;">Luggage Charge Details:</label>
                                        </div>
                                    </div>
                                    <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                        @if(count($reservation_luggage_info) > 0)
                                        <div class="col-12" style="flex: 0 0 100%;max-width: 100%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;">
                                            <div class="" id="popup_luggages_table">
                                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                    <div class="col-xl-10 col-12 ml-auto luggage-wrap" style="display:flex;flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                                        <label class="kt-mr-20 kt-mt-10" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 73%;font-weight: 700;">Luggage Type.</label>
                                                        <label class="kt-mr-20 kt-mt-10" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 10%;margin-left:3.5%;font-weight: 700;">Charge</label>
                                                        <label class="kt-mr-20 kt-mt-10" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 10%;margin-left:3.5%;font-weight: 700;">Total Fare</label>
                                                    </div>
                                                </div>
                                                <div>
                                                    @foreach($reservation_luggage_info as $records)
                                                        @if(count($records['system_luggage_def']) > 0)

                                                        <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                            <div class="col-xl-10 col-12 ml-auto luggage-wrap" style="display:flex;flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                                                <label class="kt-mt-10 kt-mr-20" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 73%;">{{ $records['system_luggage_def'][0]['v_name'] }} </label>
                                                                <label class="kt-mt-10 kt-mr-20" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 10%;margin-left:3.5%;">  {{ $records['system_luggage_def'][0]['d_unit_price'] }} </label>
                                                                <label class="kt-mt-10 kt-mr-20" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 10%;margin-left:3.5%;"> {{ $records['d_price'] }} </label>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @endforeach

                                                    @if(count($reservation_pet_info) > 0)
                                                        @foreach($reservation_pet_info as $records)
                                                            @if(count($records['system_animal_def']) > 0)
                                                            <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                                                <div class="col-xl-10 col-12 ml-auto luggage-wrap" style="display:flex;flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                                                    <label class="kt-mt-10 kt-mr-20" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 73%;">
                                                                        {{ $records['system_animal_def'][0]['v_name'] }} </label>
                                                                    <label class="kt-mt-10 kt-mr-20" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 10%;margin-left:3.5%;">
                                                                        {{ $records['system_animal_def'][0]['d_unit_price'] }} </label>
                                                                    <label class="kt-mt-10 kt-mr-20" style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;width: 10%;margin-left:3.5%;">
                                                                        {{ $records['system_animal_def'][0]['d_unit_price'] }} </label>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row" style="display: flex;flex-wrap: wrap;margin-right: -10px;margin-left: -10px;">
                                    <div class="col-xl-10 col-12 ml-auto kt-mt-10" style="flex: 0 0 83.3333333333%;max-width: 83.3333333333%;padding-right: 10px;padding-left: 10px;box-sizing: border-box;margin-left: auto;">
                                        <p style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">*must travel with Full Fare Adult</p>
                                        <p style="font-size: 13px;margin-bottom: 6.5px;margin-top:0;line-height: 15px;display: block;font-family: 'Roboto', sans-serif;">**must tell us in advance</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           
           
            </div>


            <!--End::Row-->
            <!--End::Dashboard 1-->
        </div>
        <!-- end:: Content -->
    </div>

    @stop

    @section('custom_js')
    <script>
        $(document).ready(function(){
            /* $('#printPage').click(function(){
                var data = '<input type="button" value="Print this page" onClick="window.print()">';
                data += '<div id="div_print">';
                data += $('.contentToPrint').html();
                data += '</div>';

                myWindow=window.open('','','width=200,height=100');
                myWindow.innerWidth = screen.width;
                myWindow.innerHeight = screen.height;
                myWindow.screenX = 0;
                myWindow.screenY = 0;
                myWindow.document.write(data);
                myWindow.focus();
            }); */
        });
        function printData()
        {
            
            // debugger;
            // var divToPrint=document.getElementById("contentToPrint");
           /*  newWin= window.open("");
            newWin.document.write(divToPrint.outerHTML); */
            // newWin.getElementById("contentToPrint").style="display:none;"
            window.print();
            // newWin.close();
        }

        $('#printPage').on('click',function(){
            printData();
        })
    </script>
    @stop