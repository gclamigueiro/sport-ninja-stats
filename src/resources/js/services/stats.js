import axios from "axios";


export const save = (player_id, stats) => {
    return axios.post('/api/stats', {
        player_id, stats
    })
}

export const get = (order_by_stat) => {
    return axios.get('/api/stats' + (order_by_stat ? `?stat=${order_by_stat}` : ''))
}