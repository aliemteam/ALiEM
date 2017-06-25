import * as React from 'react';
import {
    LineChart,
    Line,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    ResponsiveContainer,
} from 'recharts';
import Dot from './dot';
import Tip from './tooltip';
import XAxisLabel from './x-axis-label';
import { Rank } from './';

interface State {
    activeLine: string;
}

interface Props {
    data: Rank[];
}

export default class SmiPlot extends React.PureComponent<Props, State> {
    static ticks = Array.from(Array(26).keys()).filter(Boolean);

    constructor(props) {
        super(props);
        this.state = {
            activeLine: '',
        };
    }

    hover = (e: React.MouseEvent<SVGCircleElement>) => {
        const site = e.currentTarget.id;
        this.setState(prevState => ({ ...prevState, activeLine: site }));
    };

    resetActive = () => {
        if (this.state.activeLine == '') return;
        this.setState(prevState => ({ ...prevState, activeLine: '' }));
    };

    render() {
        const sites = Object.keys(this.props.data[0]).filter(n => n !== 'date');
        const colors = getColors();
        const { activeLine } = this.state;
        return (
            <ResponsiveContainer height={1500} width="100%">
                <LineChart
                    height={1500}
                    width={1000}
                    data={this.props.data}
                    margin={{ top: 30, right: 0, left: 0, bottom: 20 }}
                >
                    <CartesianGrid strokeDasharray="3 3" />
                    <XAxis
                        dataKey="date"
                        padding={{ left: 20, right: 20 }}
                        orientation="top"
                        tick={<XAxisLabel />}
                    />
                    <YAxis
                        domain={[1, 25]}
                        reversed={true}
                        tickLine={false}
                        width={30}
                        allowDataOverflow={true}
                        scale="linear"
                        interval={0}
                        padding={{ top: 20, bottom: 20 }}
                        ticks={SmiPlot.ticks}
                    />
                    <Tooltip content={<Tip isActive={activeLine !== ''} text={activeLine} />} />
                    {sites.map(site => {
                        const c = colors.next().value;
                        return (
                            <Line
                                key={site}
                                type="linear"
                                strokeWidth={3}
                                activeDot={false}
                                dot={
                                    <Dot
                                        fill={c}
                                        id={site}
                                        onMouseOver={this.hover}
                                        onMouseOut={this.resetActive}
                                    />
                                }
                                opacity={
                                    activeLine === site || activeLine === ''
                                        ? 1
                                        : 0.3
                                }
                                dataKey={site}
                                stroke={c}
                            />
                        );
                    })}
                </LineChart>
            </ResponsiveContainer>
        );
    }
}

function* getColors() {
    // prettier-ignore
    const c = [
        '#01cadf', '#fc2b63', '#63cd42', '#1f47c9', '#a9d551', '#a10092',
        '#008609', '#db49c9', '#006b0d', '#eb80ff', '#9fa300', '#4c41b2',
        '#e8c24a', '#6189ff', '#e48300', '#0170d8', '#bc9400', '#0051a7',
        '#ff6c38', '#00b171', '#c60064', '#69dab4', '#a50063', '#008a59',
        '#ff79d0', '#305d19', '#f8a8ff', '#8a8500', '#753791', '#eac06e',
        '#494f82', '#b64a00', '#006149', '#b00026', '#5c5f00', '#feacea',
        '#765600', '#ff85aa', '#6e7641', '#ff6782', '#a76400', '#774069',
        '#ff9255', '#7f4130', '#ffb580', '#91341d', '#ffa3af', '#ff6b65',
        '#fdb59d', '#d9908d',
    ];
    let i = 0;
    while (true) {
        yield c[i];
        i = i === 49 ? 0 : i + 1;
    }
};

