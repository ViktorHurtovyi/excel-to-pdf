<template>
    <div class="container">
        <div class="row justify-content-center">
                <FileUpload v-if="form !== false"
                            :onFileChange="onFileChange"
                            :uploadFile="uploadFile"
                >
                </FileUpload>
                <h4 v-if="error===true">Something went wrong! Please try later!</h4>
                <h4 v-if="errorValidation===true">Wrong file format! Only xlsx files are valid</h4>
                <FormSteps v-if="form !== true && end === false && error===false"
                           :options=optionValue
                           :name=nameValue
                           :changeResult=changeResult
                ></FormSteps>
                <button class="btn btn-success" v-if="formEnd === true" v-on:click="()=>savePdf()">download PDF</button>
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
                nameValue: '',
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
                    alert ('Please, select the file');
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
                        if(error.response.status === 422){
                            this.errorValidation = true;
                        }else {
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
                    this.nameValue = Object.keys(this.data)[this.currentStep];
                    this.currentStep++;
                } else {
                    this.end = true;
                }
            }
        }
    }
</script>
