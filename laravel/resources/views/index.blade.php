@include('partials.header')
<div class="main-container flex fd-c">
    <div class="top-bar">
        <div class="logo-container flex fd-r">
            <img class="logo" src="{{ asset('/images/icons/logo.svg') }}" alt="">
            <h2 class="site-name">Road Inspector</h2>
        </div>
    </div>
    <div class="main-content">
        <div class="roads-content flex fd-r">
            <div class="incidents-overview">
                <div class="overview-title flex fd-c">
                    <h2>Overzicht</h2>
                </div>
                <div class="incidents flex fd-c">
                    @foreach($info as $key => $value)
                        @if($value['data'])
                        <div class="collection {{$key}} flex fd-c">
                            <div class="box {{$key}} flex" id="{{$key}}" onclick="getData(this.id)">
                                <div class="title">
                                    <h4>{{$value['name']}} ({{$value['data']}})</h4>
                                </div>
                            </div>
                            <div class="roads">
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                {{-- @if($last_updated)
                    <div class="last-updated"><p>Last updated: {{$last_updated}}</p></div>
                @endif --}}
            </div>
            <div class="maps">
                <div id="map"></div>
            </div>
        </div>

    </div>
</div>
@include('partials.footer')

