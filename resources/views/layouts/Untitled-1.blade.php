@if(isset(Auth::user()->pathfile) && json_decode(Auth::user()->pathfile,true)['profile'] !== null) background-image: url({{ asset('storage/public/file/' . json_decode(Auth::user()->pathfile,true)['profile']) }})
                        @else background-image: url({{ asset('bmtmudathemes/assets/images/avatar.jpg') }})
                        @endif


                        