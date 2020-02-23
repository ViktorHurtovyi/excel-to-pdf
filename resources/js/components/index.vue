<template>
    <div class="h-100 justify-content-center align-items-center">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-body">
                        <FileUpload v-if="form !== false"
                                    :onFileChange="onFileChange"
                                    :uploadFile="uploadFile"
                        >
                        </FileUpload>
                        <br>
                        <div  class="alert alert-danger" role="alert" v-if="error===true">Something went wrong! Please try later!</div >
                        <div  class="alert alert-danger" role="alert" v-if="errorValidation===true">Wrong file format! Only xlsx files are valid</div >
                        <FormSteps v-if="form !== true && end === false && error===false"
                                   :options=optionValue
                                   :title= title
                                   :changeResult=changeResult
                        ></FormSteps>
                        <button class="offset-4 col col-4 btn btn-primary" v-if="formEnd === true" v-on:click="()=>savePdf()">Download
                            PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FormSteps from "./steps-form/steps-form";
    import FileUpload from "./file-upload-form/file-upload";

    export default {
        components: {FormSteps, FileUpload},
        data() {
            return {
                form: true,
                formEnd: false,
                end: false,
                error: false,
                errorValidation: false,
                file: '',
                data: '',
                count: '',
                currentStep: 0,
                title: '',
                result: [],
                optionValue: [],
            }
        },
        methods: {
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.file = e.target.files[0];
            },
            uploadFile() {
                if (this.file === '')
                    alert('Please, select the file');
                else {
                    const config = {
                        headers: {'content-type': 'multipart/form-data'}
                    };
                    let formData = new FormData();
                    formData.append('file', this.file);

                    axios.post('/api/file', formData, config).then(response => {
                        this.errorValidation = false;
                        this.data = response.data;
                        this.form = false;
                        this.count = Object.keys(this.data).length;
                        this.nextStep();
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errorValidation = true;
                        } else {
                            this.error = true;
                            this.form = false;
                        }
                    });
                    this.loading = false;
                }
            },
            changeResult: function (key, value) {
                this.result.push({key, value});
                this.nextStep();
                if (Object.keys(this.result).length === this.count)
                    this.formEnd = true;
            },
            savePdf: function () {
                axios.post('/api/get-pdf', {result: this.result}, {responseType: 'arraybuffer'})
                    .then(response => {
                        let blob = new Blob([response.data], {type: 'application/pdf'});
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = 'test.pdf';
                        link.click()
                    }).catch(err => {
                    this.error = true;
                    this.form = false;
                })
            },
            nextStep: function () {
                if (this.count !== this.currentStep) {
                    this.optionValue = Object.values(this.data)[this.currentStep];
                    this.title = Object.keys(this.data)[this.currentStep];
                    this.currentStep++;
                } else {
                    this.end = true;
                }
            }
        }
    }
</script>
