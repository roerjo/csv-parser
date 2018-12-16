import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class UploadForm extends Component {


    render() {

        // remove previous results when uploading a new CSV file
        function resetTable(e) {
            e.stopPropagation();
            let newBody = document.createElement('tbody');
            newBody.id = 'reviewers';
            let oldBody = document.getElementById('reviewers');
            oldBody.parentNode.replaceChild(newBody, oldBody);
        }

        return (
            <form action="/parser" method="POST" encType="multipart/form-data">
                <input type="hidden" name="_token" value={this.props.token}/>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="fileInputAddon">Upload</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" id="fileInput" class="custom-file-input" name="csv_data"/>
                        <label class="custom-file-label" for="fileInput">Choose CSV File</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" onClick={resetTable}>Submit File</button>
            </form>
        );
    }
}

if (document.getElementById('upload-form')) {
    const element = document.getElementById('upload-form');
    const props = Object.assign({}, element.dataset);
    ReactDOM.render(<UploadForm {...props} />, element);
}
