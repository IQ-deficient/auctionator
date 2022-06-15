// import http from "../http-common";
import axios from "axios";

class MultipleImageUpload {

    upload(file, onUploadProgress) {
        let formData = new FormData();
        formData.append("image", file);

        return axios.post("/image/1", formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }, onUploadProgress
        });
    }

    getFiles() {
        //not needed
    }
}

export default new MultipleImageUpload();