<!-- Bootstrap core JavaScript-->
<script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('js/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Custom scripts for all pages-->
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
<!-- Include the toast library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
@stack('scripts')
<script>
    $(document).ready(function() {

        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if (Session::has('alert-' . $msg))
                var msg = '@php echo Session("alert-".$msg); @endphp';
                @if ($msg == 'success')
                    setTimeout(() => {
                        alertSuccess(msg);
                    }, 500);
                @endif
                @if ($msg == 'danger')
                    setTimeout(() => {
                        alertDanger(msg);
                    }, 500);
                @endif
                @if ($msg == 'info')
                    setTimeout(() => {
                        alertInfo(msg);
                    }, 500);
                @endif
                @if ($msg == 'warning')
                    setTimeout(() => {
                        alertWarning(msg);
                    }, 500);
                @endif
            @endif
        @endforeach

        if (sessionStorage.getItem("SessionSuccess")) {
            alertSuccess(sessionStorage.getItem("SessionSuccess"));
            sessionStorage.removeItem('SessionSuccess');
        }
        if (sessionStorage.getItem("SessionDanger")) {
            alertWarning(sessionStorage.getItem("SessionDanger"));
            sessionStorage.removeItem('SessionDanger');
        }

        $('[data-toggle="tooltip"]').tooltip()
    });

    function alertDanger(msg) {
        $.toast({
            heading: 'Oops',
            text: msg,
            icon: 'error',
            loader: true,
            loaderBg: '#fff',
            showHideTransition: 'slide',
            hideAfter: 6000,
            position: 'bottom-right',
        })
    }

    function alertSuccess(msg) {
        $.toast({
            heading: 'Success',
            text: msg,
            icon: 'success',
            loader: true,
            loaderBg: '#fff',
            showHideTransition: 'slide',
            hideAfter: 6000,
            allowToastClose: false,
            position: 'bottom-center',
        })
    }

    function alertWarning(msg) {
        $.toast({
            heading: 'Warning',
            text: msg,
            icon: 'warning',
            loader: true,
            loaderBg: '#fff',
            showHideTransition: 'slide',
            hideAfter: 6000,
            allowToastClose: false,
            position: 'bottom-right',
        })
    }

    function alertInfo(msg) {
        $.toast({
            heading: 'Attention',
            text: msg,
            icon: 'info',
            loader: true,
            loaderBg: '#fff',
            showHideTransition: 'slide',
            hideAfter: 6000,
            allowToastClose: false,
            position: 'bottom-right',
        })
    }

    function delconf(url, title = "Do You Want To Remove This?", btnText = "Yes, Delete It ", msg =
        "Blog Deleted Successfully", location = null, disableAction = true) {
        $.confirm({
            title: 'Are You Sure ?',
            content: title,
            autoClose: 'cancel|8000',
            type: 'red',
            confirmButton: "Yes",
            cancelButton: "Cancel",
            theme: 'material',
            backgroundDismiss: false,
            backgroundDismissAnimation: 'glow',
            buttons: {
                tryAgain: {
                    text: btnText,
                    action: function() {
                        if (disableAction) {
                            $.ajax({
                                url: url,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: 'GET',
                                success: function() {
                                    sessionStorage.setItem("SessionSuccess",
                                        msg
                                    );
                                    if (location == null) {
                                        document.location.reload(true);
                                    } else {
                                        window.location.href = location;
                                    }
                                }
                            });
                        } else {
                            alertDanger(msg)
                        }
                    }
                },
                cancel: function() {}
            }
        });
    }

    function approve(url, title = "Do You Want To Approve It?", btnText = "Yes, Publish IT ", msg =
        "Blog Published Successfully", location = null) {
        $.confirm({
            title: 'Are you sure?',
            content: title,
            autoClose: 'cancel|8000',
            type: 'green',
            theme: 'material',
            backgroundDismiss: false,
            backgroundDismissAnimation: 'glow',
            buttons: {
                tryAgain: {
                    text: btnText,
                    action: function() {
                        $.ajax({
                            url: url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'GET',
                            async: false,
                            success: function(response) {
                                if (response.reject) {
                                    sessionStorage.setItem("SessionDanger",
                                        response.reject
                                    );
                                    document.location.reload(true);
                                } else {
                                    sessionStorage.setItem("SessionSuccess",
                                        msg
                                    );
                                    if (location == null) {
                                        document.location.reload(true);
                                    } else {
                                        window.location.href = location;
                                    }
                                }
                            }
                        });
                    }
                },
                cancel: function() {}
            }
        });
    }

    function decline(url, title = "Do You Want To Decline It?") {
        $.confirm({
            title: 'Are you sure?',
            content: title,
            autoClose: 'cancel|8000',
            type: 'red',
            theme: 'material',
            backgroundDismiss: false,
            backgroundDismissAnimation: 'glow',
            buttons: {
                'Yes, Unpublish IT': function() {
                    window.location.href = url;
                },
                cancel: function() {

                },

            }
        });
    }


    function setLoader(btnId, spanId) {
        $(btnId).addClass('d-none');

        $(spanId).html(
            '<div class="spinner-border spinner-border-sm" role="status"> <span class="sr-only">Loading...</span> </div>'
        );
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function setCookie(name, value, days, path = '/') {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=" + path;
    }

    function setSelect2RedBoder(optiondev) {
        $(optiondev + " .select2-selection.select2-selection--single ").css("border",
            "1px solid #ff0000");
    }
    //  $(function() {
    //      $('[data-toggle="popover"]').popover()
    //  })
    //  $(function() {
    //      $('[data-bs-toggle="tooltip"]').tooltip()
    //  })
</script>
<script>
    // alerts
    window.addEventListener('live-alert', event => {
        switch (event.detail.type) {
            case "success":
                alertSuccess(event.detail.msg);
                break;
            case "danger":
                alertDanger(event.detail.msg);
                break;
            case "warning":
                alertWarning(event.detail.msg);
                break;
            case "info":
                alertInfo(event.detail.msg);
                break;
        }
    });
</script>
