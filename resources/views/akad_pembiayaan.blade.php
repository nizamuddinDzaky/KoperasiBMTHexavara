{{--                                                                                                                                                                                                                                                                                                   <a href="{{$url}}">My Word Document</a>--}}

<iframe src="https://docs.google.com/viewer?embedded=true&url={{URL::asset($url)}}" frameborder="no" style="width:100%;height:100%"></iframe>

{{--<iframe style="float:right;" src = "{{URL::asset('ViewerJS')}}ViewerJS/#../{{$url}} width='400' height='300' allowfullscreen webkitallowfullscreen>--}}
{{--<iframe src = "{{URL::asset($url)}}/ViewerJS/#../demo/ohm2013.odp" width='400' height='300' allowfullscreen webkitallowfullscreen></iframe>--}}
{{--<iframe src ="https://docs.google.com/viewerng/viewer?url={{URL::asset($url)}}">--}}

{{--</iframe>--}}
{{--<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=<span style="color: #3366ff;">https://img.labnol.org/di/PowerPoint.ppt</span>' width='1278px' height='350px' frameborder='0'</iframe>--}}
{{--<iframe src="https://docs.google.com/a/'{{$url}}'?widget=true&amp;headers=true" style="width:100%;height:100%;"></iframe>--}}
{{--</iframe>--}}