<div class="field-doubleselectrelation" data-fieldname="$Name[]" <% if $AllowPreview %>data-preview="$Link('preview')"<% end_if %>>

    <p class="help"> Hold down "Control", or "Command" on a Mac, to select more than one.</p>

    <div class="selector">
        <div class="selector-available">
            <h2>Available</h2>
            <div class="selector-filter">
                <input type="text" class="text" placeholder="Filter Available">
            </div>
            <ul class="dsr-available form-control">
                <% loop $Options %><% if not $isChecked %>
                <li data-value="$Value"><span>$Title</span></li>
                <% end_if %><% end_loop %>
            </ul>
            <div class="dsr-actions">
                <button title="Click to choose all Applicable $Title at once." class="btn btn-outline-primary font-icon-tick dsr-chooseall">Choose all</button>
                <button title="Choose" class="btn btn-primary font-icon-plus  dsr-add">Choose</button>
            </div>
        </div>
        <div class="selector-chosen">
            <h2>Chosen</h2>
            <div class="selector-filter">
                <input type="text" class="text" placeholder="Filter Chosen">
            </div>
            <ul class="dsr-chosen form-control">
                <% loop $Options %><% if $isChecked %>
                <li data-value="$Value"><span>$Title</span></li>
                <% end_if %><% end_loop %>
            </ul>
            <div class="dsr-actions">
                <button title="Remove" class="btn btn-danger font-icon-minus dsr-remove">Remove</button>
                <button title="Click to remove all chosen Applicable $Title at once." class="btn btn-outline-danger font-icon-trash-bin dsr-remove-all">Remove all</button>
            </div>
        </div>
    </div>
    <% loop $Options %><% if $isChecked %>
    <input type="hidden" class="dsr-input" name="$Up.Name[]" value="$Value">
    <% end_if %><% end_loop %>

    <div class="DSRPreviewModal modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">$DataTitle Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
</div>
