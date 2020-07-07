import React from 'react';
import Typography from '@material-ui/core/Typography';

export default function SevenTagRoster() {
  return (
    <React.Fragment>
      <Typography variant="subtitle2">
        Result: 1/2-1/2
      </Typography>
      <Typography variant="subtitle2" color="textSecondary">
        Event: Saint Louis Blitz 2017
      </Typography>
      <Typography variant="subtitle2" color="textSecondary">
        Site: Saint Louis USA
      </Typography>
      <Typography variant="subtitle2" color="textSecondary">
        Date: 2017.08.18
      </Typography>
      <Typography variant="subtitle2" color="textSecondary">
        Round: 18.2
      </Typography>
      <Typography variant="subtitle2" color="textSecondary">
        White: Kasparov,G
      </Typography>
      <Typography variant="subtitle2" color="textSecondary">
        Black: Navara,D
      </Typography>
      <Typography variant="subtitle2" color="textSecondary">
        WhiteElo: 2812
      </Typography>
      <Typography variant="subtitle2" color="textSecondary">
        BlackElo: 2737
      </Typography>
    </React.Fragment>
  );
}
