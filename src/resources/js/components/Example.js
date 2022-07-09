import ReactDOM from 'react-dom';

import { useState } from 'react';

import { save, get } from '../services/player_stats';

function Example() {

    const [amount, setAmount] = useState(10);
    // const [times, setTimes] = useState([]);

    const generateStats = () => {

        const promises = [];

        for (let i = 0; i < amount; i++) {

            const player_id = Math.floor(Math.random() * 100);

            const stats = [
                {
                    name: "goals",
                    value: Math.floor(Math.random() * 100)
                },
                {
                    name: "assists",
                    value: Math.floor(Math.random() * 100)
                },
                {
                    name: "points",
                    value: Math.floor(Math.random() * 100)
                },
                {
                    name: "games_played",
                    value: Math.floor(Math.random() * 100)
                },
                {
                    name: "shots",
                    value: Math.floor(Math.random() * 100)
                }
            ]

            promises.push(save(player_id, stats));
        }

        Promise.all(promises)

    }

    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-8">
                    <div className="card">
                        <h3 className="card-header">Generate Stats</h3>
                        <label>Number of petitions</label>
                        <input
                            value={amount}
                            onChange={(evt) => setAmount(evt.target.value)}
                        ></input>

                        <button onClick={generateStats} >Send</button>

                    </div>
                </div>
               {/* <div>
                    {times.map((time, index) => {
                        return <p>{(index + 1) + " - player_id:" + time.player_id  + " - " + time.duration + ' seconds'} </p>
                    })}
                </div> */}
            </div>
        </div>
    );
}

export default Example;

if (document.getElementById('root')) {
    ReactDOM.render(<Example />, document.getElementById('root'));
}
