define(['jquery'], function($) {
    return {
        init: function() {
            var featureurl = M.cfg.wwwroot + '/local/inactive_users/delete.php';
            $('#inactive_users-myInput').on('keyup', function() {
                var input, filter, table, tr;
                input = $(this);
                filter = input.val().toUpperCase();
                table = $('#myTable');
                tr = table.find('tr');
                tr.each(function(index) {
                    if (index !== 0) {
                        $(this).hide();
                        var firstname = $(this).find('td').eq(1).text().toUpperCase();
                        var lastname = $(this).find('td').eq(2).text().toUpperCase();
                        var email = $(this).find('td').eq(3).text().toUpperCase();

                        if (firstname.indexOf(filter) > -1 || lastname.indexOf(filter) > -1 || email.indexOf(filter) > -1) {
                            $(this).show();
                        }
                    }
                });
            });

            // Toggle Check All functionality
            $('#checkAll').on('click', function() {
                var source = this;
                var checkboxes = $('.user-checkbox');
                checkboxes.each(function() {
                    $(this).prop('checked', source.checked);
                });
            });

            // Suspend selected users functionality
            $('#suspendSelected').on('click', function() {
                var selectedIds = $.map($('.user-checkbox:checked'), function(checkbox) {
                    return $(checkbox).data('userid');
                });

                if (selectedIds.length > 0) {
                    selectedIds.forEach(function(userid) {
                        var suspendUrl = `${featureurl}?userid=${userid}&action=1`;

                        $.ajax({
                            url: suspendUrl,
                            type: 'POST',
                            success: function() {
                                location.reload();
                            },
                            error: function() {
                                alert('Failed to suspend some users. Please try again.');
                            }
                        });
                    });
                } else {
                    alert('Please select at least one user to suspend.');
                }
            });

            // Delete selected users functionality
            $('#deleteSelected').on('click', function(event) {
                var selectedIds = $.map($('.user-checkbox:checked'), function(checkbox) {
                    return $(checkbox).data('userid');
                });

                if (selectedIds.length === 0) {
                    alert('Please select at least one user to delete.');
                    event.stopImmediatePropagation();
                    return;
                }

                $('#confirmDeleteSelected').modal('show');
            });

            // Confirm delete functionality
            $('#confirmDeleteSelectedButton').on('click', function() {
                var selectedIds = $.map($('.user-checkbox:checked'), function(checkbox) {
                    return $(checkbox).data('userid');
                });

                if (selectedIds.length > 0) {
                    selectedIds.forEach(function(userid) {
                        var deleteUrl = `${featureurl}?userid=${userid}&action=2`;

                        $.ajax({
                            url: deleteUrl,
                            type: 'POST',
                            success: function() {
                                location.reload();
                            },
                            error: function(error) {
                                alert(`Failed to delete user ID ${userid}: ${error}`);
                            }
                        });
                    });
                    $('#confirmDeleteSelected').modal('hide');
                }
            });
        }
    };
});