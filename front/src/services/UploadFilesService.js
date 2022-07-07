import axios from "axios";

class UploadFilesService {

    upload(file, onUploadProgress) {
        let formData = new FormData();
        formData.append("image", file);

        axios.get('/auth/user')
            .then(response => {
                if (response.data) {

                    return axios.post("/image/" + response.data.id, formData, {
                        headers: {
                            "Content-Type": "multipart/form-data"
                        }, onUploadProgress
                    });
                }
            })
            .catch(error => {
                console.log(error)
            })
    }
}

export default new UploadFilesService();