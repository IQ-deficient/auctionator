// import http from "../http-common";
import axios from "axios";

class MultipleImageUpload {

    upload(file, item_id, onUploadProgress) {
        let formData = new FormData();
        formData.append("image", file);

        return axios.post("/images/" + item_id, formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }, onUploadProgress
        });
    }
}

export default new MultipleImageUpload();